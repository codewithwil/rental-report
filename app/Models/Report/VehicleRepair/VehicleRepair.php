<?php

namespace App\Models\Report\VehicleRepair;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Models\User,
    Models\Files\Files,
    Traits\HasUploadFile,
    Traits\ActivityLogs,
    Models\Transactions\Vehicle\VehicleRepairRealiz
};

use Illuminate\{
    Database\Eloquent\Model
};

class VehicleRepair extends Model
{
    use HasUploadFile, ActivityLogs;
    const STATUSREP_PENDING   = 1;
    const STATUSREP_INPROG    = 2;
    const STATUSREP_COMPLETED = 3;
    const STATUSREP_REJECTED  = 4;
    const STATUS_INACTIVE     = 0;
    const STATUS_ACTIVE       = 1;
    protected $table          = 'vehicle_repairs';
    protected $primaryKey     = 'vehicleRepId';
    protected $fillable       = [
        'vehicle_id', 'user_id', 'submission_date', 'description', 
        'statusRepair', 'estimated_cost', 'status'
    ];

    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');}
    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function photo(){return $this->morphMany(Files::class, 'fileable');}
    public function vehicleRepairReal(){return $this->hasMany(VehicleRepairRealiz::class, 'vehicleRep_id', 'vehicleRepId');}

    public function getStatusRepairLabelAttribute()
    {
        $labels = [
            self::STATUSREP_PENDING     => 'Pending',
            self::STATUSREP_INPROG      => 'Dalam Proses',
            self::STATUSREP_COMPLETED   => 'Disetujui',
            self::STATUSREP_REJECTED    => 'Ditolak',
        ];
    
        return $labels[$this->statusRepair] ?? 'Tidak Diketahui';
    }
}
