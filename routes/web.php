<?php

use App\Http\Controllers\API\Auth\AuthC;
use App\Http\Controllers\API\Dashboard\DashboardC;
use App\Http\Controllers\API\Front\FrontC;
use Illuminate\Support\Facades\Route;
use App\Events\TestBroadcastEvent;
use App\Models\Notification\Notification;
use Illuminate\Support\Facades\Broadcast;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['guest'])->group(function(){
    Route::get('/login', [AuthC::class, 'index'])->name('login');
    Route::get('/register', [AuthC::class, 'regis'])->name('regis');
    Route::post('/login', [AuthC::class, 'login']);
    Route::post('/register', [AuthC::class, 'register'])->name('register');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/logout', [AuthC::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardC::class, 'index'])->name('dashboard');

    Route::group(["prefix" => "/people", "as" => "people."], __DIR__ . "/web/people/index.php");
    Route::group(["prefix" => "/configuration", "as" => "configuration."], __DIR__ . "/web/configuration/index.php");
    Route::group(["prefix" => "/setting", "as" => "setting."], __DIR__ . "/web/setting/index.php");
    Route::group(["prefix" => "/report", "as" => "report."], __DIR__ . "/web/report/index.php");
});

Route::get('/notifications/latest', function () {
    $user = auth()->user();
    $notifications = \App\Models\Notification\Notification::where('user_id', $user->id)
        ->where('is_read', 0)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($notifications);
})->middleware('auth');

Route::post('/notifications/{id}/mark-read', function ($id) {
    $notif = \App\Models\Notification\Notification::find($id);
    if ($notif && $notif->user_id == auth()->id()) {
        $notif->update(['is_read' => 1]);
        return response()->json(['status' => 'ok']);
    }
    return response()->json(['status' => 'forbidden'], 403);
})->middleware('auth');
