<?php

use Illuminate\{
    Database\Migrations\Migration,
    Database\Schema\Blueprint,
    Support\Facades\Schema
};

return new class extends Migration
{

    public function up(): void
    {
         if(!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('activityLogId');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->tinyInteger('action'); 
                $table->string('model');
                $table->unsignedBigInteger('model_id')->nullable(); 
                $table->text('description')->nullable();
                $table->json('data')->nullable(); 
                $table->ipAddress('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
