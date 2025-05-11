<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'feed_name',
        'feed_type',
        'quantity',
        'unit_price',
        'purchase_date',
        'expiry_date',
        'supplier',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the total value of this feed inventory
     */
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Get all usage records for this feed
     */
    public function usages()
    {
        return $this->hasMany(FeedUsage::class);
    }

    /**
     * Get all flocks that have used this feed
     */
    public function flocks()
    {
        return $this->belongsToMany(Flock::class, 'feed_usage')
            ->withPivot('amount_used', 'usage_date')
            ->withTimestamps();
    }

    /**
     * Check if feed is low in stock
     */
    public function isLowStock($threshold = 10)
    {
        return $this->quantity <= $threshold;
    }

    /**
     * Check if feed is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }

        return now()->diffInDays($this->expiry_date, false) <= $days
            && now()->diffInDays($this->expiry_date, false) >= 0;
    }
}
