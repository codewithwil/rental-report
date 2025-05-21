<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Rules\RulesC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Rules\RulesC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Rules\RulesC::class, 'invoice'])->name("invoice");
Route::get("/edit/{rulesId}", [ctr\API\Resources\Rules\RulesC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Rules\RulesC::class, 'store'])->name("store");
Route::post("/update/{rulesId}", [ctr\API\Resources\Rules\RulesC::class, 'update'])->name("update");