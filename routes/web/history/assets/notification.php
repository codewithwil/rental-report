
<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get('/', [ctr\API\History\HistoryC::class, 'getNotification']);
