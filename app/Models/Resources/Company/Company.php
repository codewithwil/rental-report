<?php

namespace App\Models\Resources\Company;

use App\{
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Company extends Model
{
    use ActivityLogs;
    protected $table           = 'companies';
    protected $primaryKey      = 'companyId';
    protected $fillable        = [
        'companyId','image','name', 'web'
    ];

    protected static function boot(){parent::boot();}
    public function branch(){return $this->hasOne(Company::class, 'company_id', 'companyId');}
}

