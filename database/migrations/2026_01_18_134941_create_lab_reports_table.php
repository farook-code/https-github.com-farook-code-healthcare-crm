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
    Schema::create('lab_reports', function (Blueprint $table) {
        $table->id();

        $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
        $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
        $table->foreignId('uploaded_by')->constrained('users');

        $table->string('title'); // e.g. Blood Test, X-Ray
        $table->string('file_path')->nullable();
        $table->string('file_type')->nullable(); // pdf / image
        $table->string('status')->default('uploaded'); // requested, completed, uploaded
        $table->timestamp('generated_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};
