<?php

use App\Http\Controllers\HolidayPlanController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::middleware('auth:api')->group(function () {

    Route::get('/holidays', [HolidayPlanController::class, 'index']);
    Route::post('/holidays', [HolidayPlanController::class, 'store']);
    Route::get('/holidays/{id}', [HolidayPlanController::class, 'show']);
    Route::put('/holidays/{id}', [HolidayPlanController::class, 'update']);
    Route::delete('/holidays/{id}', [HolidayPlanController::class, 'destroy']);
    Route::get('/holidays/{id}/pdf', [HolidayPlanController::class, 'generatePdf']);
});
