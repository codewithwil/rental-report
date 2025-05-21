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
          if(!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('vehicleId');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('brand_id');
                $table->string('photo', 200);
                $table->string('name', 50);
                $table->string('plate_number', 20);
                $table->string('color', 20);
                $table->year('year');
                $table->date('last_inspection_date');
                $table->date('kir_expiry_date')->nullable(true); 
                $table->date('tax_date')->nullable(false); 
                $table->text('note');
                $table->tinyInteger('status')->default(2);
                $table->timestamps();

                $table->foreign('branch_id')
                ->references('branchId')
                ->on('branches')
                ->onUpdate("cascade")
                ->onDelete("restrict");

                $table->foreign('category_id')
                ->references('categoryId')
                ->on('categories')
                ->onUpdate("cascade")
                ->onDelete("restrict");

                $table->foreign('brand_id')
                ->references('brandId')
                ->on('brands')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
