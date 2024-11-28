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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('survey_name');
            $table->text('description')->nullable();
            $table->boolean('anonymous')->default(false);
            $table->enum('status', ['Open', 'Closed']);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('editable_by_another_user', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });

        Schema::create('survey_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
        
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('question');
            $table->enum('question_type', ['Multiple Choice', 'Open-ended']);
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('question');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('answer');
        });
        
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('submitted_at');
            $table->unique(['survey_id', 'user_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('survey_participants');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_submissions');
    }
};
