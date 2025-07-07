<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'invoice'])->name("invoice");
Route::get("/show/{vehcileRepairRealId}", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'show'])->name("show");
Route::get("/pdf/{vehcileRepairRealId}", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'pdf'])->name("pdf");
Route::get("/edit/{vehcileRepairRealId}", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'store'])->name("store");
Route::post("/update/{vehcileRepairRealId}", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'update'])->name("update");
Route::post("/delete/{vehcileRepairRealId}", [ctr\API\Transactions\Vehicle\VehicleRepairRealizC::class, 'delete'])->name("delete");