<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedUsage extends Model
{
    use HasFactory;

    protected $table = 'feed_usage';

    protected $fillable = [
        'feed_id',
        'flock_id',
        'amount_used',
        'usage_date',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'amount_used' => 'decimal:2',
    ];

    /**
     * Get the feed that was used
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    /**
     * Get the flock that used the feed
     */
    public function flock()
    {
        return $this->belongsTo(Flock::class);
    }

    /**
     * Calculate the cost of this feed usage
     */
    public function getCostAttribute()
    {
        return $this->amount_used * ($this->feed ? $this->feed->unit_price : 0);
    }
}
