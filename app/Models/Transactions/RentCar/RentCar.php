<?php

namespace App\Models\Transactions\RentCar;

use App\{
    Models\Files\Files,
    Models\Resources\Vehicle\Vehicle,
    Models\Transactions\Payment\PaymentAmount,
    Traits\ActivityLogs,
    Traits\HasUploadFile,

};

use Illuminate\{
    Database\Eloquent\Model
};

class RentCar extends Model
{
    use HasUploadFile, ActivityLogs;
    const STATUS_PENDING  = 0;
    const STATUS_ONRENT   = 1;
    const STATUS_KEMBALI  = 2;
    const STATUS_CANCEL   = 3;
    protected $table      = 'rent_cars';
    protected $primaryKey = 'rentCarId';
    protected $fillable   = [
        'vehicle_id', 'renter_name', 'renter_address', 'renter_phone',
        'startDate', 'endDate', 'pricePerDay', 'notes', 'status'
    ];

    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');}

    public function paymentAmount()
    {
        return $this->morphMany(PaymentAmount::class, 'payable', 'payable_type', 'payable_id', 'rentCarId');
    }
    public function photo(){return $this->morphMany(Files::class, 'fileable');}
    public function getVehicleNameAttribute(){return $this->vehicle?->name;}
    public function getReportDateAttribute(){return $this->startDate;}
}
