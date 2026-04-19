<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentFixedCharge extends Model
{
    protected $fillable = [
        'rent_report_id',
        'title',
        'amount',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function report()
    {
        return $this->belongsTo(RentReport::class, 'rent_report_id');
    }
}
