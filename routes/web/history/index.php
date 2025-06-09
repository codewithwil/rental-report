<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/notification", "as"     => "notification."], __DIR__ . "/assets/notification.php");
Route::group(["prefix" => "/activities", "as"     => "activities."], __DIR__ . "/assets/activities.php");