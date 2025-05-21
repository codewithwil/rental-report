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
        if(!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('employeeId');
                $table->unsignedBigInteger('user_id');
                $table->string('foto', 200);
                $table->string('name', 75);
                $table->unsignedBigInteger('telepon');
                $table->text('address');
                $table->tinyInteger('gender');
                $table->date('birthdate');
                $table->date('hire_date');
                $table->decimal('salary', 10,2)->nullable(true);
                $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('employees');
    }
};
