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
        if(!Schema::hasTable('rules')) {
            Schema::create('rules', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('rulesId');
                $table->text('content');
                $table->timestamps();
            });
        }
    }
    
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
