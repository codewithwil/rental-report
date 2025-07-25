

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/users", "as"     => "users."], __DIR__ . "/assets/users.php");
Route::group(["prefix" => "/admin", "as"     => "admin."], __DIR__ . "/assets/admin.php");
Route::group(["prefix" => "/supervisor", "as"     => "supervisor."], __DIR__ . "/assets/supervisor.php");
Route::group(["prefix" => "/employee", "as"     => "employee."], __DIR__ . "/assets/employee.php");