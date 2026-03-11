<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/{short_code}', [RedirectController::class, 'handle'])->name('redirect');
