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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->ipAddress('ip_address');
            $table->foreignId('category_id')->constrained('user_log_categories')->onDelete('cascade');
            $table->foreignId('list_id')->constrained('user_log_lists')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('unknown_user_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->ipAddress('ip_address');
            $table->string('route_name');
            // $table->foreignId('category_id')->constrained('user_log_categories')->onDelete('cascade');
            // $table->foreignId('list_id')->constrained('user_log_lists')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
        Schema::dropIfExists('unknown_user_logs');
    }
};
