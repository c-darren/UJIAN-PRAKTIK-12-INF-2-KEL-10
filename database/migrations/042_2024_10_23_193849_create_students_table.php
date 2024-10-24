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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->enum('gender',['Laki-laki', 'Perempuan']);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->enum('religion', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('student_address');
            $table->smallInteger('student_postal_code');
            $table->string('parents_address');
            $table->smallInteger('parents_postal_code');
            $table->string('parents_phone');
            $table->string('father_full_name');
            $table->string('mother_full_name');
            $table->smallInteger('number_of_siblings');
            $table->string('disability')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['Pelajar', 'Sudah Lulus', 'Dikeluarkan', 'Pindah Sekolah'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
