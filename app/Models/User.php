<?php

namespace App\Models;

use App\{
    Models\People\Admin\Admin,
    Models\People\Customers\Customers,
    Models\People\Employee\Employee as EmployeeEmployee,
    Models\People\Supervisor\Supervisor,
    Models\Resources\Branch\Branch,
};
use App\Models\Notification\Notification;
use App\Models\Report\WeeklyReport\WeeklyReport;
use App\Models\Resources\Vehicle\Vehicle;
use Illuminate\{
    Database\Eloquent\Factories\HasFactory,
    Foundation\Auth\User as Authenticatable,
    Notifications\Notifiable,
};

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'email',
        'password',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    public function admin(){return $this->hasOne(Admin::class, 'user_id');}
    public function supervisor(){return $this->hasOne(Supervisor::class, 'user_id');}
    public function employee(){return $this->hasOne(EmployeeEmployee::class, 'user_id');}
    public function weeklyReport(){return $this->hasOne(WeeklyReport::class, 'user_id');}
    public function notification(){return $this->hasOne(Notification::class, 'user_id');}
    public function vehicle(){return $this->hasOne(Vehicle::class, 'user_id');}
    public function realName()
    {
        if ($this->hasRole('admin') && $this->admin) {
            return $this->admin->name;
        }

        if ($this->hasRole('supervisor') && $this->supervisor) {
            return $this->supervisor->name;
        }

        if ($this->hasRole('employee') && $this->employee) {
            return $this->employee->name;
        }

        return $this->name; 
    }


}
