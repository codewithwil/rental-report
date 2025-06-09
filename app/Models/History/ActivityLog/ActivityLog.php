<?php

namespace App\Models\History\ActivityLog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    const ACTION_CREATE     = 1;
    const ACTION_UPDATE     = 2;
    const ACTION_DELETE     = 3;
    protected $table        = 'activity_logs';
    protected $primaryKey   = 'activityLogId';
    protected $fillable     = [
        'user_id', 'action', 'model', 'model_id', 
        'description', 'data', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(){return $this->belongsTo(User::class);}
    public function getActionLabelAttribute()
    {
        return match ($this->action) {
            self::ACTION_CREATE => 'Create',
            self::ACTION_UPDATE => 'Update',
            self::ACTION_DELETE => 'Delete',
            default => 'Unknown',
        };
    }
}
