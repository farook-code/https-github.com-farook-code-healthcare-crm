<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_reports', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable()->change();
            // Add extra fields for structured data support
            $table->string('result_data')->nullable(); // JSON or text
            $table->string('status')->default('final');
            $table->timestamp('generated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lab_reports', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable(false)->change();
            $table->dropColumn(['result_data', 'status', 'generated_at']);
        });
    }
};
