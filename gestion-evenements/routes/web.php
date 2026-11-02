<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;

// Page d'accueil : redirection vers la liste des événements
Route::get('/', function () {
    return redirect()->route('events.index');
});

// CRUD complet pour les événements
Route::resource('events', EventController::class);

// Inscription à un événement (simulation)
Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');

// Annulation d'une inscription
Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');

// Consultation des inscriptions (Mes Inscriptions)
Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.index');
