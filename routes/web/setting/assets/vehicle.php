<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Vehicle\VehicleC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Vehicle\VehicleC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Vehicle\VehicleC::class, 'invoice'])->name("invoice");
Route::get("/show/{vehicleId}", [ctr\API\Resources\Vehicle\VehicleC::class, 'show'])->name("show");
Route::get("/edit/{vehicleId}", [ctr\API\Resources\Vehicle\VehicleC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Vehicle\VehicleC::class, 'store'])->name("store");
Route::post("/update/{vehicleId}", [ctr\API\Resources\Vehicle\VehicleC::class, 'update'])->name("update");
Route::post("/delete/{vehicleId}", [ctr\API\Resources\Vehicle\VehicleC::class, 'delete'])->name("delete");