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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->boolean('refill_requested')->default(false);
            $table->timestamp('refill_requested_at')->nullable();
            $table->string('refill_status')->default('none'); // none, requested, approved, denied
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['refill_requested', 'refill_requested_at', 'refill_status']);
        });
    }
};
