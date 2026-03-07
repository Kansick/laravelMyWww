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
        Schema::table('rent_list', function (Blueprint $table) {
            // Добавляем поле title (строка)
            $table->string('title')->comment('Наименование отчета об арендной плате')->after('id');
            
            // Добавляем поле discrepancy (JSON)
            $table->json('discrepancy')->nullable()->comment('Расхождение между стартом и концом счетчиков')->after('propertys_values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_list', function (Blueprint $table) {
            $table->dropColumn(['title', 'discrepancy']);
        });
    }
};
