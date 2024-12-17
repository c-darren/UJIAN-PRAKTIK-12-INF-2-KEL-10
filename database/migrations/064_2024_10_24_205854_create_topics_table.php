<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //This table only contain a topic for class_lists, subject already placed in class_lists table
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->string('topic_name');
            $table->timestamps();
        });

        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->datetime('attendance_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    
        Schema::create('class_presences', function (Blueprint $table) {
            $table->foreignId('attendance_id')->constrained('class_attendances')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alfa'])->default('Hadir');
            $table->primary(['attendance_id', 'user_id']);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};