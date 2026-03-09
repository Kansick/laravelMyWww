<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentList extends Model
{
    protected $table = 'rent_list';

    // 1. Указываем, какие поля можно заполнять массово (через create())
    protected $fillable = [
        'title',
        'date_create',
        'attributes',
        'propertys',
        'propertys_values',
        'result_sum'
    ];

    // 2. Автоматическое преобразование типов данных
    protected $casts = [
        'date_create' => 'date',      // Превращает строку "2024-05-20" в объект даты Carbon
        'result_sum'  => 'decimal:2', // Гарантирует 2 знака после запятой (например, "1500.50")
        'attributes'       => 'array', // превращает массив PHP в JSON и наоборот PHP ARRAY <=> JSON
        'propertys'        => 'array',
        'propertys_values' => 'array'
    ];
}