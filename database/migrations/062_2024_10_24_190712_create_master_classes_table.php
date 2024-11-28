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
        //Kelas 10, 11, 12
        Schema::create('master_classes', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('master_class_name');
            $table->string('master_class_code')->unique();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->enum('status', ['Archived', 'Active']);
            $table->timestamps();
        });

        Schema::create('master_class_students', function (Blueprint $table) {
            $table->id();
            $table->string('master_class_id', 255);
            $table->foreign('master_class_id')->references('id')->on('master_classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_classes');
        Schema::dropIfExists('master_class_students');
    }
};
