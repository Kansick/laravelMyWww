<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RentController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('services')->group(function () {
    Route::get('/', function () {
        return view('services.index');
    });
    Route::get('/passwords', function () {
        return view('services.passwords.index');
    });
    Route::get('/rent', [RentController::class, 'index'])->name('rent.index');
});

Route::get('/api/rent/{id}', [RentController::class, 'getRecord'])->name('api.rent.show');
