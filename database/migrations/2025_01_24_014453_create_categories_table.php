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
        if(!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('categoryId');
                $table->string('name', 50);
                $table->tinyInteger('type');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
