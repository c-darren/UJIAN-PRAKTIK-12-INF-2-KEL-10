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
        Schema::create('ppdbs', function (Blueprint $table) {
            $table->id();
            
            // Foreign key for user_id with explicit naming
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('student_name', 255)->index();
            $table->string('student_nisn', 10)->unique()->index();
            $table->string('email', 255);
            $table->string('phone', 15);
            $table->enum('status', ['Not Verifed', 'Verified'])->default('Not Verifed');
            $table->timestamp('verified_at')->nullable();

            // Foreign key for verified_by with explicit naming
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdbs');
    }
};
