<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    return view('welcome');
});



// Route pour enregistrer l'inscription d'un utilisateur à un événement
Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');

// Route pour annuler une inscription
Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
