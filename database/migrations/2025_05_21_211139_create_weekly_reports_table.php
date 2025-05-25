e<?php

use Illuminate\{
    Database\Migrations\Migration,
    Database\Schema\Blueprint,
    Support\Facades\Schema
};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         if(!Schema::hasTable('weekly_reports')) {
            Schema::create('weekly_reports', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('weekReportId');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('vehicle_id');
                $table->date('report_date');
                $table->text('note')->nullable(true);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate("cascade")
                ->onDelete("restrict");
                $table->foreign('vehicle_id')
                ->references('vehicleId')
                ->on('vehicles')
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
        Schema::dropIfExists('weekly_reports');
    }
};
