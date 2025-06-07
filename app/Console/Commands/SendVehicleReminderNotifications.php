<?php

namespace App\Console\Commands;

use Illuminate\{
    Console\Command,
    Support\Facades\Log
};

use App\{
    Models\User,
    Models\Resources\Vehicle\Vehicle,
    Events\VehicleReminderNotification,
    Models\Notification\Notification
};
use Carbon\Carbon;

class SendVehicleReminderNotifications extends Command
{
    protected $signature = 'vehicle:send-reminders';
    protected $description = 'Send vehicle document expiration reminders';

public function handle()
    {
        $vehicles = Vehicle::with('vehicleDocument')->get();
        $now = Carbon::now();

        foreach ($vehicles as $vehicle) {
            $doc = $vehicle->vehicleDocument;

            if (!$doc) continue; 

            $dates = [
                'STNK' => $doc->stnk_date,
                'KIR'  => $doc->kir_expiry_date,
                'BPKB' => $doc->bpkb_date,
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

                    $userId = $vehicle->user_id;
                    $user = User::find($userId);

                    if ($user) {
                        $notif = Notification::create([
                            'user_id' => $user->id,
                            'title' => "Reminder $type",
                            'message' => $message,
                            'link' => url("/setting/vehicle/show/{$vehicle->vehicleId}"),
                        ]);

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
