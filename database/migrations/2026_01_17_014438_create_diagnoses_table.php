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
      Schema::create('diagnoses', function (Blueprint $table) {
    $table->id();

    $table->foreignId('appointment_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('doctor_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('patient_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->text('symptoms')->nullable();
    $table->text('diagnosis');
    $table->text('notes')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
