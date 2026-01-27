<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image',
        'is_active',
        'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_menu')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'menu_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
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
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
