<?php

namespace App\Models\Resources\Category;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Category extends Model
{
    use ActivityLogs;
    const TYPE_CAR        = 1;
    const TYPE_MOTORCYCLE = 2;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'categories';
    protected $primaryKey = 'categoryId';
    protected $fillable   = [
        'name', 'status', 'type'
    ];

    protected static function boot(){parent::boot();}
    public function vehicle(){return $this->hasMany(Vehicle::class, 'category_id', 'categoryId');}
    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_CAR        => 'Mobil',
            self::TYPE_MOTORCYCLE => 'Motor',
        ];
    
        return $labels[$this->type] ?? 'Tidak Diketahui';
    }
}