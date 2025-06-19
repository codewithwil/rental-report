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
        if(!Schema::hasTable('payment_amounts')) {
            Schema::create('payment_amounts', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->id('payAmountId');
                $table->string('payable_id', 15);
                $table->string('payable_type', 100);
                $table->tinyInteger('type')->default(1)->comment('1 = masuk, 2 = keluar');
                $table->decimal('amount', 12, 2); 
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                $table->index(['payable_id', 'payable_type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_amounts');
    }
};
