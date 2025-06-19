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
        if(!Schema::hasTable('files')) {
            Schema::create('files', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('filesId');
                $table->string('path');
                $table->string('original_name')->nullable();
                $table->unsignedBigInteger('size');
                $table->string('mime_type')->nullable();
            
                $table->unsignedBigInteger('fileable_id');
                $table->string('fileable_type');
                $table->index(['fileable_id', 'fileable_type']);
                $table->timestamps();
            });
        }
    }


    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
