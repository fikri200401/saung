<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'booking_id',
        'reservation_id',
        'user_id',
        'treatment_id',
        'menu_id',
        'doctor_id',
        'saung_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function saung()
    {
        return $this->belongsTo(Saung::class);
    }

    /**
     * Scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
