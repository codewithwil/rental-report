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
        if(!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('customerId');
                $table->unsignedBigInteger('user_id');
                $table->string('foto', 200);
                $table->string('name', 75);
                $table->unsignedBigInteger('telepon');
                $table->text('address');
                $table->decimal('saldo', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
