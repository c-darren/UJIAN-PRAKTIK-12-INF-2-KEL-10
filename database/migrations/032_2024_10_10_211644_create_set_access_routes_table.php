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
        Schema::create('access_routes', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->foreignId('prefix_id')->nullable()->constrained('access_routes_prefixes')->onDelete('set null'); // Mengaitkan dengan prefix
            $table->string('page_url');
            //Menandakan prefix atau tidak
            $table->string('type');
            $table->string('method');
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('editor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            //Blacklist/Whitelist
            $table->string('type_ip_address')->nullable();

            $table->string('status');
            $table->dateTime('start_date');
            $table->dateTime('valid_until')->nullable();

            //Group List: Blacklist/Whitelist
            $table->string('type_group_list');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['page_url', 'type']);
        });

        Schema::create('routes_access_role', function (Blueprint $table) {
            $table->foreignId('set_access_id')->constrained('access_routes')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->primary(['set_access_id', 'role_id']);
        });
        
        Schema::create('routes_access_group', function (Blueprint $table) {
            $table->foreignId('set_access_id')->constrained('access_routes')->onDelete('cascade');
            $table->foreignId('group_list_id')->constrained('group_lists')->onDelete('cascade');
            $table->primary(['set_access_id', 'group_list_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_routes');
        Schema::dropIfExists('routes_access_role');
        Schema::dropIfExists('routes_access_group');
    }
};
