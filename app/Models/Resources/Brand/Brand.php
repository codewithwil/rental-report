<?php

namespace App\Models\Resources\Brand;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Traits\ActivityLogs,
};

use Illuminate\{
    Database\Eloquent\Model,
};

class Brand extends Model
{
    use ActivityLogs;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'brands';
    protected $primaryKey = 'brandId';
    protected $fillable   = [
        'name', 'status',
    ];

    protected static function boot(){parent::boot();}
    public function vehicle(){return $this->hasMany(Vehicle::class, 'brand_id', 'brandId');}
}
