<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'total_eggs',
        'damaged_eggs',
        'collection_date',
        'collected_by',
        'notes'
    ];

    protected $casts = [
        'collection_date' => 'date',
    ];

    public function flock()
    {
        return $this->belongsTo(Flock::class);
    }

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function getGoodEggsAttribute()
    {
        return $this->total_eggs - $this->damaged_eggs;
    }
}
