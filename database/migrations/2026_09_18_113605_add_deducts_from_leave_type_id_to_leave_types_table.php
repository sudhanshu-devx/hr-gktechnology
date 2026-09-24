<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->foreignId('deducts_from_leave_type_id')
                ->nullable()
                ->after('is_paid')
                ->constrained('leave_types')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropForeign(['deducts_from_leave_type_id']);
            $table->dropColumn('deducts_from_leave_type_id');
        });
    }
};