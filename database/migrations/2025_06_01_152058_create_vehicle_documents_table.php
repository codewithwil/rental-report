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
          if(!Schema::hasTable('vehicle_documents')) {
            Schema::create('vehicle_documents', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('vehicleDocId');
                $table->unsignedBigInteger('vehicle_id');
                $table->date('kir_expiry_date')->nullable(true); 
                $table->date('stnk_date')->nullable(false); 
                $table->date('bpkb_date')->nullable(false); 
                $table->string('kir_document', 200)->nullable();
                $table->string('bpkb_document', 200)->nullable();
                $table->string('stnk_document', 200)->nullable();
                $table->timestamps();

                $table->foreign('vehicle_id')
                ->references('vehicleId')
                ->on('vehicles')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_documents');
    }
};
