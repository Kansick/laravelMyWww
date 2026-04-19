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
        Schema::create('rent_meter_readings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rent_report_id')
                ->constrained('rent_reports')   
                ->cascadeOnDelete();

            $table->string('meter_name');

            $table->decimal('tariff', 12, 4)->default(0);
            $table->decimal('start_value', 12, 4)->default(0);
            $table->decimal('end_value', 12, 4)->default(0);

            $table->decimal('consumption', 12, 4)->default(0);
            $table->decimal('amount', 12, 2)->default(0);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_meter_readings');
    }
};
