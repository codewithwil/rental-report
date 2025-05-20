

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/company", "as"     => "company."], __DIR__ . "/assets/company.php");