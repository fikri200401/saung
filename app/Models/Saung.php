<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saung extends Model
{
    use HasFactory;

    protected $table = 'saungs';

    protected $fillable = [
        'name',
        'capacity',
        'location',
        'description',
        'image',
        'price_per_hour',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price_per_hour' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function schedules()
    {
        return $this->hasMany(SaungSchedule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'saung_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->feedbacks()->avg('rating') ?? 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_per_hour, 0, ',', '.');
    }

    /**
     * Check if saung is available on specific date and time
     */
    public function isAvailable($date, $startTime, $endTime)
    {
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        
        // Check if saung has schedule on that day
        $schedule = $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return false;
        }

        // Normalize time format for comparison (HH:MM)
        $startTime = substr($startTime, 0, 5);
        $endTime = substr($endTime, 0, 5);
        
        // Extract time from schedule
        $scheduleStart = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
        $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');

        // Check if requested time is within saung's operating hours
        if ($startTime < $scheduleStart || $endTime > $scheduleEnd) {
            return false;
        }

        // Normalize date for comparison
        $dateOnly = date('Y-m-d', strtotime($date));

        // Check for conflicting reservations
        $reservations = $this->reservations()
            ->whereDate('reservation_date', $dateOnly)
            ->whereIn('status', ['auto_approved', 'waiting_deposit', 'deposit_confirmed'])
            ->get();

        foreach ($reservations as $reservation) {
            $reservationStart = substr($reservation->reservation_time, 0, 5);
            $reservationEnd = substr($reservation->end_time, 0, 5);

            // Check for overlap
            if ($startTime < $reservationEnd && $endTime > $reservationStart) {
                return false; // There's an overlap
            }
        }

        return true; // No conflicts
    }
}
