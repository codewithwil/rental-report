<?php

namespace App\Http\Controllers\API\Notification;

use App\{
    Http\Controllers\Controller,
    Models\Notification\Notification
};

use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Str
};

class NotificationC extends Controller
{
    public function latestUnread()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->get()
            ->groupBy(function ($item) {
                if (Str::contains($item->link, '/setting')) {
                    return 'Setting';
                } elseif (Str::contains($item->link, '/report')) {
                    return 'Report';
                } else {
                    return 'Other';
                }
            });

        return response()->json($notifications);
    }

    public function markRead($id)
    {
        $notif = Notification::find($id);
        if ($notif && $notif->user_id == Auth::id()) {
            $notif->update(['is_read' => 1]);
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'forbidden'], 403);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'notifications'   => 'required|array',
            'notifications.*' => 'integer|exists:notifications,id',
        ]);
        $userId  = Auth::user()->id;
        $ids     = $request->input('notifications');
        $deleted = Notification::whereIn('id', $ids)
            ->where('user_id', $userId)
            ->delete();

        return redirect()->back()
            ->with('success', "Notifikasi Berhasil di hapus.");
    }


    public function destroyGroup(Request $request)
    {
        $request->validate([
            'group' => 'required|string',
        ]);

        $userId = Auth::id();
        $group = $request->input('group');

        $notifications = Notification::where('user_id', $userId)->get();
        $filtered = $notifications->filter(function ($item) use ($group) {
            if ($group === 'Setting') {
                return Str::contains($item->link, '/setting');
            } elseif ($group === 'Report') {
                return Str::contains($item->link, '/report');
            } elseif ($group === 'Other') {
                return !Str::contains($item->link, ['/setting', '/report']);
            }
            return false;
        });

        $ids = $filtered->pluck('id');

        $deletedCount = Notification::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', "Berhasil menghapus $deletedCount notifikasi di grup $group.");
    }
}
