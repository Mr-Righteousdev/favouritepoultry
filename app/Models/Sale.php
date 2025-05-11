<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'flock_id',
        'quantity',
        'unit',
        'unit_price',
        'total_amount',
        'customer_name',
        'customer_contact',
        'sale_date',
        'recorded_by',
        'notes'
    ];

    protected $casts = [
        'sale_date' => 'date',
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
