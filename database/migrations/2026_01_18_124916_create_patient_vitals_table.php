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
    Schema::create('patient_vitals', function (Blueprint $table) {
        $table->id();

        $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
        $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
        $table->foreignId('recorded_by')->constrained('users');

        // Vitals
        $table->decimal('height', 5, 2)->nullable();       // cm
        $table->decimal('weight', 5, 2)->nullable();       // kg
        $table->string('blood_pressure')->nullable();      // 120/80
        $table->integer('pulse')->nullable();              // bpm
        $table->decimal('temperature', 4, 1)->nullable();  // °C
        $table->integer('oxygen_level')->nullable();       // %

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_vitals');
    }
};
