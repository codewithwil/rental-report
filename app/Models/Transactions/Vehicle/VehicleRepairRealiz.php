<?php

namespace App\Models\Transactions\Vehicle;

use App\{
    Models\Transactions\Payment\PaymentAmount,
    Models\Files\Files,
    Traits\ActivityLogs,
    Traits\HasUploadFile,
    Models\Report\VehicleRepair\VehicleRepair
};

use Illuminate\{
    Database\Eloquent\Model
};

class VehicleRepairRealiz extends Model
{
    use HasUploadFile, ActivityLogs;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'vehicle_repair_realizs';
    protected $primaryKey = 'vehcileRepairRealId';
    protected $fillable   = [
        'vehicleRep_id', 'completeDate', 'notes', 'status'
    ];

    public function vehicleRepair(){return $this->belongsTo(VehicleRepair::class, 'vehicleRep_id', 'vehicleRepId');}

    public function paymentAmount()
    {
        return $this->morphMany(PaymentAmount::class, 'payable', 'payable_type', 'payable_id', 'vehcileRepairRealId');
    }

    public function photo(){return $this->morphMany(Files::class, 'fileable');}

}
