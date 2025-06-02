
<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get('/latest', [ctr\API\Notification\NotificationC::class, 'latestUnread']);
Route::post('/bulkDestroy', [ctr\API\Notification\NotificationC::class, 'destroy'])->name('bulkDestroy');
Route::post('/{id}/mark-read', [ctr\API\Notification\NotificationC::class, 'markRead']);
Route::post('/deleteGroup', [ctr\API\Notification\NotificationC::class, 'destroyGroup'])->name('deleteGroup');
