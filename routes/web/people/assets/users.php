<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\User\UserC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\People\User\UserC::class, 'invoice'])->name("invoice");