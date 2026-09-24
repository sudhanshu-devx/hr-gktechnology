<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {

            // ✅ Only drop FK if it actually exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'leave_requests'
                  AND COLUMN_NAME = 'approved_by'
                  AND CONSTRAINT_NAME = 'leave_requests_approved_by_foreign'
            ");

            if (! empty($foreignKeys)) {
                $table->dropForeign('leave_requests_approved_by_foreign');
            }

            // Ensure column exists + is nullable
            if (! Schema::hasColumn('leave_requests', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            } else {
                $table->unsignedBigInteger('approved_by')->nullable()->change();
            }

            // Recreate FK safely
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {

            // Drop FK only if exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'leave_requests'
                  AND COLUMN_NAME = 'approved_by'
                  AND CONSTRAINT_NAME = 'leave_requests_approved_by_foreign'
            ");

            if (! empty($foreignKeys)) {
                $table->dropForeign('leave_requests_approved_by_foreign');
            }

            // Make NOT NULL again
            $table->unsignedBigInteger('approved_by')->nullable(false)->change();

            // Recreate original FK
            $table->foreign('approved_by')
                ->references('id')
                ->on('users');
        });
    }
};
