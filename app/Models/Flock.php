<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'breed',
        'type',
        'initial_quantity',
        'current_quantity',
        'acquisition_date',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function feedConsumptions()
    {
        return $this->hasMany(FeedConsumption::class);
    }

    public function eggProductions()
    {
        return $this->hasMany(EggProduction::class);
    }

    public function mortalityRecords()
    {
        return $this->hasMany(MortalityRecord::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    public function getMortalityRate()
    {
        return $this->initial_quantity > 0
            ? (($this->initial_quantity - $this->current_quantity) / $this->initial_quantity) * 100
            : 0;
    }
}
