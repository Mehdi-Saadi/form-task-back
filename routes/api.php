<?php

use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/entry')->group(function () {
    Route::get('/', [EntryController::class, 'index']);
    Route::post('/', [EntryController::class, 'store']);
    Route::post('/photo', [EntryController::class, 'uploadPhoto']);
    Route::delete('/photo', [EntryController::class, 'destroyPhoto']);
});
