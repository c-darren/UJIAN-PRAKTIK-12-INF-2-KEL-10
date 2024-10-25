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
        //Bila Teacher di hapus, maka test tidak akan dihapus
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('test_name');
            $table->string('test_code');
            $table->enum('status', ['Active', 'Inactive']);
            $table->string('test_description')->nullable();
            $table->string('test_type');
            $table->enum('scrambled', ['Yes', 'No']);
            $table->string('test_duration');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('pending_result', ['Yes', 'No']);
            $table->smallInteger('min_score');
            $table->smallInteger('max_score');
            $table->smallInteger('passing_score');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('editable_by_another_user', ['Yes', 'No'])->default('No');
            $table->foreignId('edited_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });

        //If: untuk mendeteksi test_question hanya boleh dibuat oleh created_by pada test

        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->string('question');
            $table->enum('question_type', ['Multiple Choice', 'True or False']);
            $table->string('answer')->nullable();
            //Allowed to input more than 1 correct answer
            $table->enum('answer_type', ['Correct Answer', 'Wrong Answer']);
            $table->string('image')->nullable();
            $table->smallInteger('score');
        });

        Schema::create('test_temporary_student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('test_questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('student_answer')->nullable(); // Simpan jawaban siswa tanpa constraint foreign key
        });

        //Jika student_id berasal dari kelas yang sama dengan kolom class_id,
        //Maka yang diperbolehkan mengikuti test hanya student_id tersebut
        //Jika student_id kosong, maka semua student_id dari class_id diperbolehkan untuk ikut test
        Schema::create('test_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
        });

        //Data yang sudah tersimpan tidak akan dihapus meskipun test_participants berbeda dari sebelumnya
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('class_lists')->onDelete('set null');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('question');
            $table->string('student_answer');
            $table->string('correct_answer');
        });

        // Tabel 'test_scores'
        Schema::create('test_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('class_lists')->onDelete('set null');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->smallInteger('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_scores');
        Schema::dropIfExists('test_results');
        Schema::dropIfExists('test_participants');
        Schema::dropIfExists('test_temporary_student_answers');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('tests');
        
    }
};
