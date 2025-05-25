<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         if(!Schema::hasTable('weekly_report_details')) {
            Schema::create('weekly_report_details', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('weekReportDetId');
                $table->unsignedBigInteger('weekReport_id');
                $table->string('component', 75); 
                $table->string('position', 75);
                $table->string('file_path', 200);  
                $table->string('file_type', 50);  
                $table->timestamps();

                $table->foreign('weekReport_id')
                ->references('weekReportId')
                ->on('weekly_reports')
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
        Schema::dropIfExists('weekly_report_details');
    }
};
