<?php

namespace App\Models\Resources\Vehicle;

use App\{
    Models\Resources\Branch\Branch,
    Models\Resources\Brand\Brand,
    Models\Resources\Category\Category
};

use Illuminate\{
    Database\Eloquent\Model
};

class Vehicle extends Model
{
    const STATUS_DELETED  = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE   = 2;
    const STATUS_REPAIR   = 3;
    protected $table      = 'vehicles';
    protected $primaryKey = 'vehicleId';
    protected $fillable   = [
        'branch_id', 'category_id', 'brand_id', 'photo', 'name', 
        'plate_number', 'color', 'year', 'last_inspection_date', 
        'kir_expiry_date', 'tax_date' ,'note', 'status'
    ];

    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    public function category(){return $this->belongsTo(Category::class, 'category_id', 'categoryId');}
    public function Brand(){return $this->belongsTo(Brand::class, 'brand_id', 'brandId');}

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DELETED  => 'Dihapus',
            self::STATUS_INACTIVE => 'Tidak aktif',
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_REPAIR   => 'Perbaikan',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
}
