<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AntrianController;

Route::middleware('api')->group(function () {
    Route::get('/antrian', [AntrianController::class, 'index']);
    Route::post('/antrian', [AntrianController::class, 'store']);
    Route::get('/antrian/{id}', [AntrianController::class, 'show']);
    Route::delete('/antrian/{id}', [AntrianController::class, 'destroy']);
    Route::get('/quota', [AntrianController::class, 'quota']);
});
