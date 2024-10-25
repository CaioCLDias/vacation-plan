<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/holidays', 'HolidayController@index');
    Route::post('/holidays', 'HolidayController@store');
    Route::get('/holidays/{id}', 'HolidayController@show');
    Route::put('/holidays/{id}', 'HolidayController@update');
    Route::delete('/holidays/{id}', 'HolidayController@destroy');
    Route::get('/holidays/{id}/pdf', 'HolidayController@generatePDF');
});
