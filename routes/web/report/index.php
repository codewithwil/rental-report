

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/weeklyReport", "as"     => "weeklyReport."], __DIR__ . "/assets/weeklyReport.php");