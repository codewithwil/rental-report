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
        if(!Schema::hasTable('owners')) {
            Schema::create('owners', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('ownerId');
                $table->unsignedBigInteger('user_id');
                $table->string('foto', 200);
                $table->string('name', 75);
                $table->unsignedBigInteger('telepon');
                $table->text('address');
                $table->timestamps();

                $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
