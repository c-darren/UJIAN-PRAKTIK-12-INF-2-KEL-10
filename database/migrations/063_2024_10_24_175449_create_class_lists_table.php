<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //Subject will be placed in this table
    public function up(): void
    {   
        Schema::create('class_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_class_id')->constrained('master_classes')->onDelete('cascade');
            $table->string('class_name');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('class_code')->unique();
            $table->enum('enrollment_status', ['Open', 'Closed']);
            $table->enum('status', ['Archived', 'Active']);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('class_teachers', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->primary(['class_id', 'teacher_id']);
        });
        
        Schema::create('class_students', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->primary(['class_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_lists');
        Schema::dropIfExists('class_teachers');
        Schema::dropIfExists('class_students');
    }
};
