<?php

namespace App\Models\Transactions\RentCar;

use App\{
    Models\Transactions\RentCar\RentCar,
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class ReturnRentCar extends Model
{
    use ActivityLogs;
    protected $table      = 'return_rent_cars';
    protected $primaryKey = 'returnRentCId';
    protected $fillable   = [
        'rentCar_id', 'return_date', 'return_name', 'return_address',
        'return_phone', 'notes',
    ];

    public function rentCar(){return $this->belongsTo(RentCar::class, 'rentCar_id','rentCarId');}
}
