<?php

namespace App\Models\Resources\Vehicle;

use Illuminate\Database\Eloquent\Model;

class VehicleDocument extends Model
{
    protected $table      = 'vehicle_documents';
    protected $primaryKey = 'vehicleDocId';
    protected $fillable   = [
        'vehicle_id','kir_expiry_date', 'stnk_date', 'bpkb_date', 
        'kir_document','bpkb_document', 'stnk_document'
    ];

    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');}
}
