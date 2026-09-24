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
        $table->decimal('hra', 10, 2)->default(0)->after('basic_salary');
        $table->decimal('special_allowance', 10, 2)->default(0)->after('hra');
        $table->decimal('other_allowance', 10, 2)->default(0)->after('special_allowance');
    });
}

public function down(): void
{
    Schema::table('payrolls', function (Blueprint $table) {
        $table->dropColumn(['hra', 'special_allowance', 'other_allowance']);
    });
}
};
