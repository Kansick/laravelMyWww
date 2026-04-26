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
    Route::get('/rent', [RentController::class, 'index'])->name('rent.index');
});

Route::prefix('api/rent')->group(function () {
    Route::get('/last', [RentController::class, 'getLast'])->name('api.rent.show.last');
    Route::get('/{id}', [RentController::class, 'getRecord'])->name('api.rent.show');
    Route::post('/create', [RentController::class, 'createRecord'])->name('api.rent.create');
});

Route::prefix('tiers')->group(function () {
    Route::get('/', function () {
        return view('tiers.index');
    });
    Route::get('/games', function() { return view('tiers.games.index'); });
    Route::get('/books', function() { return view('tiers.books.index'); });
    Route::get('/media', function() { return view('tiers.media.index'); });
});