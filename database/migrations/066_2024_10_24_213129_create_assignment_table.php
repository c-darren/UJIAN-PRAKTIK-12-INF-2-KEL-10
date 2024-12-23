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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->string('assignment_name');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('accept_late_submissions')->default(false);
            $table->text('attachment_file_name')->nullable();
            $table->text('attachment')->nullable();
            $table->foreignId('editor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('attachment_file_name')->nullable();
            $table->text('attachment')->nullable();
            $table->double('score')->nullable();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('return_status', ['draft', 'scheduled', 'returned'])->default('draft');
            $table->timestamp('scheduled_return_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
        Schema::create('assignment_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
