<?php

namespace App\Models\Report\WeeklyReport;

use Illuminate\Database\Eloquent\Model;

class WeeklyReportDetail extends Model
{
    const TYPE_PHOTO      = 1;
    const TYPE_VIDEO      = 2;
    protected $table      = 'weekly_report_details';
    protected $primaryKey = 'weekReportDetId';
    protected $fillable   = [
        'weekReport_id' ,'component', 'position',  'file_type', 
        'file_path'
    ];

    public function weeklyReport(){
        return $this->belongsTo(weeklyReport::class, 'weekReport_id', 'weekReportId');
    }
}
