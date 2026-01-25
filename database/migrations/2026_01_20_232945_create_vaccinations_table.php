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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            
            $table->string('vaccine_name'); // e.g. "COVID-19 Pfizer", "Tetanus"
            $table->string('dose_number')->nullable(); // e.g. "1st Dose", "Booster"
            $table->date('administered_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
