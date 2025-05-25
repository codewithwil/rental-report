<?php

use App\Console\Commands\SendVehicleReminderNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


// Artisan::command('vehicle:send-reminders', function () {
//     $command = new SendVehicleReminderNotifications();
//     $command->handle();
// });

app(Schedule::class)->command('vehicle:send-reminders')->everyMinute();