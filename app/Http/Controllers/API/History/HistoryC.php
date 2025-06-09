<?php

namespace App\Http\Controllers\API\History;

use App\{
    Http\Controllers\Controller,
    Models\Notification\Notification,
    Models\History\ActivityLog\ActivityLog,
};

class HistoryC extends Controller
{
    public function getNotification()
    {
        $notification = Notification::select('id', 'user_id', 'title', 'message', 'is_read', 'created_at')
            ->with(
                'user.admin:adminId,user_id,name', 
                'user.supervisor:supervisorId,user_id,name', 
                'user.employee:employeeId,user_id,name'
            )
            ->latest()
            ->get();

        return view('admin.history.notification', compact('notification'));
    }

    public function getActivities()
    {
        $activities = ActivityLog::select([
                'activityLogId',
                'user_id',
                'action',
                'description',
                'ip_address',
                'user_agent',
                'created_at'
            ])
            ->with(
                'user.admin:adminId,user_id,name', 
                'user.supervisor:supervisorId,user_id,name', 
                'user.employee:employeeId,user_id,name'
            )
            ->latest()
            ->get();

        return view('admin.history.activities', compact('activities'));
    }

}
