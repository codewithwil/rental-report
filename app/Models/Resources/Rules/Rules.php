<?php

namespace App\Models\Resources\Rules;

use App\{
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Rules extends Model
{
    use ActivityLogs;

    protected $table      = 'rules';
    protected $primaryKey = 'rulesId';
    protected $fillable   = ['content'];
    protected static function boot(){parent::boot();}
}
