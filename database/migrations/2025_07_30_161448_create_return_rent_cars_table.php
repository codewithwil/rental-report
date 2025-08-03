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
        if(!Schema::hasTable('return_rent_cars')) {
            Schema::create('return_rent_cars', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->id('returnRentCId');
                $table->unsignedBigInteger('rentCar_id');
                $table->date('return_date');
                $table->string('return_name', 75);
                $table->text('return_address')->nullable(false);
                $table->string('return_phone', 20)->nullable(false);
                $table->text('notes');
                $table->timestamps();
                
                $table->foreign('rentCar_id')->references('rentCarId')->on('rent_cars')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('return_rent_cars');
    }
};
