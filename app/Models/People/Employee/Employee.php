<?php

namespace App\Models\People\Employee;

use App\{
    Models\Scopes\UserBranchScope,
    Models\User,
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Employee extends Model
{
    use ActivityLogs;
    const JENKEL_LAKILAKI  = 0;
    const JENKEL_PEREMPUAN = 1;
    protected $table       = 'employees';
    protected $primaryKey  = 'employeeId';
    protected $fillable    = [
        'user_id', 'foto', 'name', 'telepon', 'address', 'gender',
        'birthdate', 'hire_date', 'salary', 'status'
    ];

    protected static function boot(){parent::boot();}
    protected static function booted(){static::addGlobalScope(new UserBranchScope);}
    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function getGenderLabelAttribute()
    {
        $labels = [
            self::JENKEL_LAKILAKI  => 'Laki-laki',
            self::JENKEL_PEREMPUAN => 'Perempuan',
        ];
    
        return $labels[$this->gender] ?? 'Tidak Diketahui';
    }
}
