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
        if(!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('branchId');
                $table->unsignedBigInteger('company_id');
                $table->text('address');
                $table->string('email', 75)->unique();
                $table->string('operationalHours', 50);    
                $table->bigInteger('phone');    
                $table->decimal('ltd', 65, 30)->nullable(true);    
                $table->decimal('lng', 65, 30)->nullable(true);    
                $table->tinyInteger('status')->default(1);
                $table->timestamps();


                $table->foreign('company_id')
                ->references('companyId')
                ->on('companies')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
