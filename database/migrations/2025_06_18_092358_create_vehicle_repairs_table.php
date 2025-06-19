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
        if(!Schema::hasTable('vehicle_repairs')) {
            Schema::create('vehicle_repairs', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('vehicleRepId');
                $table->unsignedBigInteger('vehicle_id');
                $table->unsignedBigInteger('user_id'); 
                $table->date('submission_date');
                $table->text('description');
                $table->tinyInteger('statusRepair')->comment('status', ['pending', 'in_progress', 'completed', 'rejected'])->default(1);
                $table->decimal('estimated_cost', 12, 2)->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                $table->foreign('vehicle_id')->references('vehicleId')->on('vehicles')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_repairs');
    }
};
