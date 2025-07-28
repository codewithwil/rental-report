<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Report\Kas\KasC::class, 'index'])->name("index");
Route::get("/pdf", [ctr\API\Report\Kas\KasC::class, 'pdf'])->name("pdf");