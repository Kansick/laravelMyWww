<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentMeterReading extends Model
{
    protected $fillable = [
        'rent_report_id',
        'meter_name',
        'tariff',
        'start_value',
        'end_value',
        'consumption',
        'amount',
        'sort_order',
    ];

    protected $casts = [
        'tariff' => 'decimal:4',
        'start_value' => 'decimal:4',
        'end_value' => 'decimal:4',
        'consumption' => 'decimal:4',
        'amount' => 'decimal:2',
    ];

    public function report()
    {
        return $this->belongsTo(RentReport::class, 'rent_report_id');
    }
}
