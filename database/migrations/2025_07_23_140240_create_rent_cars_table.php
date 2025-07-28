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
        if(!Schema::hasTable('rent_cars')) {
            Schema::create('rent_cars', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->id('rentCarId');
                $table->unsignedBigInteger('vehicle_id');
                $table->string('renter_name', 75);
                $table->text('renter_address')->nullable(false);
                $table->string('renter_phone', 20)->nullable(false);
                $table->date('startDate');
                $table->date('endDate');
                $table->decimal('pricePerDay', 12, 2); 
                $table->text('notes')->nullable(true);
                $table->tinyInteger('status')->default(0);

                $table->timestamps();
                $table->foreign('vehicle_id')->references('vehicleId')->on('vehicles')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_cars');
    }
};
