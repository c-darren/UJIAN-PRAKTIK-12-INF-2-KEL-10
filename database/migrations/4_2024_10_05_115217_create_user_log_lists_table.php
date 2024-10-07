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
        Schema::create('user_log_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('user_log_categories')->onDelete('cascade');
            $table->string('method');
            $table->string('route_name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            //Untuk memastikan kombinasi method dan route_name unik
            $table->unique(['method', 'route_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_log_lists');
    }
};
