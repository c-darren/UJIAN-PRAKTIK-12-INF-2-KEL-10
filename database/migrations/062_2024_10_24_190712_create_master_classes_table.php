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
            $table->id();
            $table->string('master_class_name')->index();
            $table->string('master_class_code')->unique()->index();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->enum('status', ['Archived', 'Active'])->default('Active')->index();
            $table->timestamps();
        });

        Schema::create('master_class_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_class_id')->constrained('master_classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Enrolled', 'Exited'])->default('Enrolled')->index();
            $table->timestamps();
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
