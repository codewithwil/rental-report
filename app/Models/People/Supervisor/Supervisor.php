<?php

namespace App\Models\People\Supervisor;

use App\{
    Models\Scopes\UserBranchScope,
    Models\User,
    Traits\ActivityLogs
};
use Illuminate\{
    Database\Eloquent\Model
};

class Supervisor extends Model
{
    use ActivityLogs;
    protected $table      = 'supervisors';
    protected $primaryKey = 'supervisorId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon',
    ];

    protected static function boot(){parent::boot();}
    protected static function booted(){static::addGlobalScope(new UserBranchScope);}
    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
}
