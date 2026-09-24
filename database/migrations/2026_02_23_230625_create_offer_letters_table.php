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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('position');

            $table->enum('employment_type', ['intern', 'employee']);

            $table->decimal('salary', 12, 2)->nullable();
            $table->decimal('stipend', 12, 2)->nullable();

            $table->string('duration')->nullable();

            $table->enum('location', ['Prayagraj', 'Lucknow']);

            $table->string('pdf_path')->nullable();

            $table->foreignId('generated_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_letters');
    }
};
