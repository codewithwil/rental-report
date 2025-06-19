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
       if(!Schema::hasTable('vehicle_repair_realizs')) {
            Schema::create('vehicle_repair_realizs', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('vehcileRepairRealId');
                $table->unsignedBigInteger('vehicleRep_id');
                $table->date('completeDate');
                $table->text('notes')->nullable(true);
                $table->tinyInteger('status')->default(1);

                $table->foreign('vehicleRep_id')->references('vehicleRepId')->on('vehicle_repairs')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_repair_realizs');
    }
};
