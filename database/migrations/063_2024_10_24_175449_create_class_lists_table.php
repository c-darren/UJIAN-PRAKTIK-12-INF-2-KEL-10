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
        Schema::create('class_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_class_id')->constrained('master_classes')->onDelete('cascade');
            $table->string('class_name');
            $table->string('class_code')->unique();
            //Student Enrollment Status Open, Closed
            $table->enum('enrollment_status',['Open', 'Closed']);
            $table->enum('status',['Archived', 'Active']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('set null');
            $table->primary(['class_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_lists');
    }
};
