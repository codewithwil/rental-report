<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\RentCar\RentCarC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Transactions\RentCar\RentCarC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Transactions\RentCar\RentCarC::class, 'invoice'])->name("invoice");
Route::get("/show/{rentCarId}", [ctr\API\Transactions\RentCar\RentCarC::class, 'show'])->name("show");
Route::get("/pdf/{rentCarId}", [ctr\API\Transactions\RentCar\RentCarC::class, 'pdf'])->name("pdf");
Route::get("/edit/{rentCarId}", [ctr\API\Transactions\RentCar\RentCarC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Transactions\RentCar\RentCarC::class, 'store'])->name("store");
Route::post("/update/{rentCarId}", [ctr\API\Transactions\RentCar\RentCarC::class, 'update'])->name("update");
Route::post("/delete/{rentCarId}", [ctr\API\Transactions\RentCar\RentCarC::class, 'delete'])->name("delete");