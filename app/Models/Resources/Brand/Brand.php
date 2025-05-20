<?php

namespace App\Models\Resources\Brand;

use App\{
    Models\Resources\Vehicle\Vehicle,
};
use Illuminate\{
    Database\Eloquent\Model,
};

class Brand extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'brands';
    protected $primaryKey = 'brandId';
    protected $fillable   = [
        'name', 'status',
    ];

    public function vehicle(){return $this->hasMany(Vehicle::class, 'brand_id', 'brandId');}
}
