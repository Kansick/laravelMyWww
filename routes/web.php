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

Route::prefix('api/rent')->group(function () {
    Route::get('/last', [RentController::class, 'getLast'])->name('api.rent.show.last');
    Route::get('/{id}', [RentController::class, 'getRecord'])->name('api.rent.show');
    Route::post('/create', [RentController::class, 'createRecord'])->name('api.rent.create');
});