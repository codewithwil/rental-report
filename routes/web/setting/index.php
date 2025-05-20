

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/branch", "as"     => "branch."], __DIR__ . "/assets/branch.php");
Route::group(["prefix" => "/category", "as"     => "category."], __DIR__ . "/assets/category.php");
Route::group(["prefix" => "/rules", "as"     => "rules."], __DIR__ . "/assets/rules.php");
Route::group(["prefix" => "/brand", "as"     => "brand."], __DIR__ . "/assets/brand.php");
Route::group(["prefix" => "/vehicle", "as"     => "vehicle."], __DIR__ . "/assets/vehicle.php");