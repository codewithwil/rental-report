<?php

namespace App\Models\Transactions\Vehicle;

use App\{
    Models\Transactions\Payment\PaymentAmount
};

use Illuminate\{
    Database\Eloquent\Model
};

class VehicleRepairRealiz extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'vehicle_repair_realizs';
    protected $primaryKey = 'vehcileRepairRealId';
    protected $fillable   = [
        'vehicleRep_id', 'completeDate', 'notes', 'status'
    ];

    public function paymentAmount()
    {
        return $this->morphMany(PaymentAmount::class, 'payable', 'payable_type', 'payable_id', 'vehcileRepairRealId');
    }

}
