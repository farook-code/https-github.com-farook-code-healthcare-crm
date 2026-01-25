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
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('claim_number')->unique();
            $table->string('provider_name'); // e.g., BlueCross, Aetna
            $table->decimal('amount_claimed', 10, 2);
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->text('rejection_reason')->nullable();
            $table->date('submitted_at');
            $table->date('responded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};
