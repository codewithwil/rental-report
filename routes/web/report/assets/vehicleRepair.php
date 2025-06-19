<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'invoice'])->name("invoice");
Route::get("/edit/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'edit'])->name("edit");
Route::get("/detail/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'detail'])->name("detail");
Route::post("/approve/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'approve'])->name("approve");
Route::post("/reject/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'reject'])->name("reject");
Route::post("/store", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'store'])->name("store");
Route::post("/update/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'update'])->name("update");
Route::post("/delete/{vehicleRepId}", [ctr\API\Report\VehicleRepair\VehicleRepairC::class, 'delete'])->name("delete");