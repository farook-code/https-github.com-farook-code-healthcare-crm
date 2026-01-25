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
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('appointment_date');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['model_type', 'model_id']);
            $table->index('created_at'); // for sorting by date
            $table->index('user_id'); // If not automatically indexed by constrained() - it usually IS, but harmless to be sure or skip. constrained usually adds fk constraint, not necessarily index on some DBs, but on MySQL it does.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['appointment_date']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
