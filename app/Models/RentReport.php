<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentReport extends Model
{
    protected $fillable = [
        'title',
        'period_date',
        'fixed_charges_sum',
        'meters_sum',
        'result_sum',
        'comment',
    ];

    protected $casts = [
        'period_date' => 'date',
        'fixed_charges_sum' => 'decimal:2',
        'meters_sum' => 'decimal:2',
        'result_sum' => 'decimal:2',
    ];

    public function fixedCharges()
    {
        return $this->hasMany(RentFixedCharge::class);
    }

    public function meterReadings()
    {
        return $this->hasMany(RentMeterReading::class);
    }
}
