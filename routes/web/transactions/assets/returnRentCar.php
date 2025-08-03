<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'invoice'])->name("invoice");
Route::get("/show/{returnRentCId}", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'show'])->name("show");
Route::get("/pdf/{returnRentCId}", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'pdf'])->name("pdf");
Route::get("/edit/{returnRentCId}", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'store'])->name("store");
Route::post("/update/{returnRentCId}", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'update'])->name("update");
Route::post("/delete/{returnRentCId}", [ctr\API\Transactions\RentCar\ReturnRentCarC::class, 'delete'])->name("delete");