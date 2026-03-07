<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/calc', function () {
        return view('services.calc.index');
    });
});
