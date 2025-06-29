<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'invoice'])->name("invoice");
Route::get("/show/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'show'])->name("show");
Route::get("/pdf/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'pdf'])->name("pdf");
Route::get("/edit/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'edit'])->name("edit");
Route::post("/approve/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'approve'])->name("approve");
Route::post("/reject/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'reject'])->name("reject");
Route::post("/store", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'store'])->name("store");
Route::post("/update/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'update'])->name("update");
Route::post("/delete/{weekReportId}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'delete'])->name("delete");