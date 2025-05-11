<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Health extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'health';

    protected $fillable = [
        'flock_id',
        'date',
        'treatment_type',
        'medication',
        'dosage',
        'diagnosis',
        'symptoms',
        'treatment_cost',
        'mortality',
        'notes',
        'next_checkup_date',
        'treated_by',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'next_checkup_date' => 'date',
        'treatment_cost' => 'decimal:2',
        'mortality' => 'integer',
    ];

    // Relationship with Flock
    public function flock()
    {
        return $this->belongsTo(Flock::class);
    }

    // Scope for active treatments
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for completed treatments
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Get treatment status color
    public function getStatusColorAttribute()
    {
        return [
            'active' => 'yellow',
            'completed' => 'green',
            'pending' => 'blue',
            'failed' => 'red',
        ][$this->status] ?? 'gray';
    }
}
