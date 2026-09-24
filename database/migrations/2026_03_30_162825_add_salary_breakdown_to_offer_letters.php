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
       Schema::table('offer_letters', function (Blueprint $table) {
    $table->decimal('basic_salary', 10, 2)->nullable();
    $table->decimal('hra', 10, 2)->nullable();
    $table->decimal('special_allowance', 10, 2)->nullable();
    $table->decimal('bonus', 10, 2)->nullable();
    $table->decimal('pf_deduction', 10, 2)->nullable();
    $table->decimal('tax_deduction', 10, 2)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offer_letters', function (Blueprint $table) {
            //
        });
    }
};
