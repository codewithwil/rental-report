<?php

namespace App\Traits;

use App\{
    Models\History\ActivityLog\ActivityLog
};

use Illuminate\{
    Support\Facades\Auth
};

trait ActivityLogs
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity(ActivityLog::ACTION_CREATE);
        });

        static::updated(function ($model) {
            $model->logActivity(ActivityLog::ACTION_UPDATE);
        });

        static::deleted(function ($model) {
            $model->logActivity(ActivityLog::ACTION_DELETE);
        });
    }

   public function logActivity(int $action, ?string $description = null)
    {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model'      => get_class($this),
            'model_id'   => $this->getKey(),
            'description'=> $description,
            'data'       => $this->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
