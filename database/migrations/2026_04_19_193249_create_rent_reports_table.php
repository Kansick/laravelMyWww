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
        Schema::create('rent_reports', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->date('period_date')->nullable();

            $table->decimal('fixed_charges_sum', 12, 2)->default(0);
            $table->decimal('meters_sum', 12, 2)->default(0);
            $table->decimal('result_sum', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_reports');
    }
};
