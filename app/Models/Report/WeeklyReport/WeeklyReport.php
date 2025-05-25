<?php

namespace App\Models\Report\WeeklyReport;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Models\User
};

use Illuminate\{
    Database\Eloquent\Model
};

class WeeklyReport extends Model
{
    const STATUS_DELETED  = 0;
    const STATUS_PENDING  = 1;
    const STATUS_APPROVE  = 2;
    const STATUS_REJECTED = 3;
    protected $table      = 'weekly_reports';
    protected $primaryKey = 'weekReportId';
    protected $fillable   = [
        'user_id' ,'vehicle_id', 'report_date', 
        'note', 'status' 
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id');}
    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id');}
    public function weeklyReportDetail() {
        return $this->hasMany(WeeklyReportDetail::class, 'weekReport_id', 'weekReportId');
    }

    public function getStatusDescription():string
    {
       $description = [
            self::STATUS_PENDING  => 'Menunggu validasi dari supervisor/admin.',
            self::STATUS_APPROVE  => 'Laporan sudah divalidasi dan disetujui.',
            self::STATUS_REJECTED => 'Laporan ditolak oleh supervisor/admin.',
        ];
        return $description[$this->status] ?? 'Tidak Diketahui';
    }

    public function getStatusDescriptionAttribute()
    {
        return $this->getStatusDescription();
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DELETED  => 'Dihapus',
            self::STATUS_PENDING  => 'Pending',
            self::STATUS_APPROVE  => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
}
