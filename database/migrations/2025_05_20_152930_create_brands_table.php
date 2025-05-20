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
        if(!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('brandId');
                $table->string('name', 50);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
