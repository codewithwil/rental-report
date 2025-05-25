<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Events\VehicleReminderNotification;
use App\Models\Notification\Notification;
use App\Models\Resources\Vehicle\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendVehicleReminderNotifications extends Command
{
    protected $signature = 'vehicle:send-reminders';
    protected $description = 'Send vehicle document expiration reminders';

    public function handle()
    {
        $vehicles = Vehicle::all();
        $now = Carbon::now();

        foreach ($vehicles as $vehicle) {
            $dates = [
                'stnk_date' => $vehicle->stnk_date,
                'kir_expiry_date' => $vehicle->kir_expiry_date,
                'bpkb_date' => $vehicle->bpkb_date,
            ];

            foreach ($dates as $type => $expireDate) {
                if (!$expireDate) continue;

                $diff = $now->diffInDays(Carbon::parse($expireDate), false);
                 Log::info("Vehicle {$vehicle->name} $type expires in $diff days.");

                $message = null;

               if ($diff > 29 && $diff <= 30) {
                    $message = "$type kendaraan {$vehicle->name} akan habis 1 bulan lagi.";
                } elseif ($diff <= 14 && $diff >= 13) {
                    $message = "$type kendaraan {$vehicle->name} akan habis 2 minggu lagi.";
                } elseif ($diff > 6 && $diff <= 7) {
                    $message = "$type kendaraan {$vehicle->name} akan habis 1 minggu lagi.";
                } elseif ($diff > 2 && $diff <= 3) {
                    $message = "$type kendaraan {$vehicle->name} akan habis 3 hari lagi.";
                } elseif ($diff === 1) {
                    $message = "$type kendaraan {$vehicle->name} akan habis BESOK.";
                }

if ($message) {
    Log::info("Create notification for vehicle {$vehicle->name}: $message");

    $users = User::role(['admin', 'supervisor'])->get();

    foreach ($users as $user) {
        $notif = Notification::create([
            'user_id' => $user->id,
            'title' => "Reminder $type",
            'message' => $message,
            'link' => url("/setting/vehicle/{$vehicle->id}"),
        ]);

        Log::info("Notification created for user {$user->id} with ID {$notif->id}");

        broadcast(new VehicleReminderNotification(
            $user->id,
            $notif->title,
            $notif->message,
            $notif->link
        ));
    }
}

            }
        }
    }
}
