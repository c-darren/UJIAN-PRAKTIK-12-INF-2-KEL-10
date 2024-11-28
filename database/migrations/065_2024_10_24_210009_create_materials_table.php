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
    {   //Sebelum posting, periksa kembali apakah user punya akses untuk posting ini (jangan melalui middleware untuk AccessRoute)
        Schema::create('materials', function (Blueprint $table) {            
            $table->id();
            $table->foreignId('class_id')->constrained('class_lists')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->string('material_name');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->string('attachment')->nullable();
            $table->foreignId('editor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            //Validasi kelas asal terlebih dahulu sebelum akses url (jangan diakses secara langsung) ini. Tidak perlu pakai token
        });
        
        Schema::create('material_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
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
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_comments');
    }
};
