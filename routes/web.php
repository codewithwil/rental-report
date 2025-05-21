<?php

use App\Http\Controllers\API\Auth\AuthC;
use App\Http\Controllers\API\Dashboard\DashboardC;
use App\Http\Controllers\API\Front\FrontC;
use Illuminate\Support\Facades\Route;

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
});