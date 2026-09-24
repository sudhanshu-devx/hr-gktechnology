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
    Schema::table('payrolls', function (Blueprint $table) {
        $table->dropColumn('allowances');
    });
}

public function down(): void
{
    Schema::table('payrolls', function (Blueprint $table) {
        $table->decimal('allowances', 10, 2)->default(0);
    });
}
};
