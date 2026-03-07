<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rent_list', function (Blueprint $table) {
            $table->id();
        
            // Дата создания расчета (Y-m-d)
            // Используем тип 'date', чтобы удобно работать с датами в Laravel
            $table->date('date_create')->comment('Дата создания расчета (Y-m-d)');
            
            // Постоянные траты за аренду
            // Используем 'text' вместо 'string', если текст может быть длинным.
            // Если нужен JSON (массив данных), замените на: $table->json('attributes');
            $table->json('attributes')->comment('Постоянные траты за аренду');
            
            // Счетчики: название = тариф
            // Пример данных: "electricity=5.5,water=30.0" или JSON {"electricity": 5.5}
            $table->json('propertys')->comment('Счетчики и тарифы (счетчик = тариф)');
            
            // Показания счетчиков: начало и конец
            // Пример данных: "electricity:100-150,water:200-210"
            $table->json('propertys_values')->comment('Показания счетчиков (начало и конец)');
            
            // Итоговая стоимость
            // decimal(10, 2) = максимум 99999999.99. Идеально для денег.
            $table->decimal('result_sum', 10, 2)->comment('Итоговая стоимость аренды');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_list');
    }
};
