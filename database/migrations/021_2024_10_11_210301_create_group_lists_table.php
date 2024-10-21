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
        Schema::create('group_lists', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            //Open, Closed, Apply
            $table->string('status');
            //Enter for join without invitation
            $table->string('code');
            $table->dateTime('valid_until');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_lists');
    }
};
