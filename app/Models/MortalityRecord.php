<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MortalityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'quantity',
        'date',
        'cause',
        'description',
        'recorded_by'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function flock()
    {
        return $this->belongsTo(Flock::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
