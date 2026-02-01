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
        Schema::create('medicine_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interacting_medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->enum('severity', ['low', 'moderate', 'high'])->default('moderate');
            $table->text('description');
            $table->timestamps();

            // Unique constraint to prevent duplicate same-pair entries
            $table->unique(['medicine_id', 'interacting_medicine_id'], 'med_inter_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_interactions');
    }
};
