<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('events', EventController::class);
Route::get('/', [EventController::class, 'index'])->name('home');
