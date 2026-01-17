<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'reservation_code',
        'user_id',
        'saung_id',
        'reservation_date',
        'reservation_time',
        'end_time',
        'number_of_people',
        'status',
        'total_price',
        'discount_amount',
        'final_price',
        'is_manual_entry',
        'admin_notes',
        'customer_notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'number_of_people' => 'integer',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'is_manual_entry' => 'boolean',
    ];

    /**
     * Get reservation_time as HH:MM format
     */
    public function getReservationTimeAttribute($value)
    {
        if (!$value) return null;
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    /**
     * Get end_time as HH:MM format
     */
    public function getEndTimeAttribute($value)
    {
        if (!$value) return null;
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            if (!$reservation->reservation_code) {
                $reservation->reservation_code = 'RSV-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saung()
    {
        return $this->belongsTo(Saung::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'reservation_menu')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'reservation_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'reservation_id');
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class, 'reservation_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['auto_approved', 'waiting_deposit', 'deposit_confirmed']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['auto_approved', 'deposit_confirmed'])
                     ->where('reservation_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('reservation_date', '<', now()->toDateString());
    }

    public function scopeWaitingDeposit($query)
    {
        return $query->where('status', 'waiting_deposit');
    }

    /**
     * Helper methods
     */
    public function needsDeposit()
    {
        $reservationDate = \Carbon\Carbon::parse($this->reservation_date);
        $daysDifference = now()->diffInDays($reservationDate, false);
        
        return $daysDifference >= 7; // Reservasi 7 hari atau lebih butuh DP
    }

    public function canBeFeedback()
    {
        return $this->status === 'completed' && !$this->feedback;
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }

    public function isPending()
    {
        return $this->status === 'waiting_deposit';
    }

    public function isConfirmed()
    {
        return in_array($this->status, ['auto_approved', 'deposit_confirmed']);
    }
}
