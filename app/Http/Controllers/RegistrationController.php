<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // 1. Valider les données du formulaire d'inscription
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
        ]);

        // 2. Vérifier s'il reste des places disponibles
        // (En comparant le nombre d'inscriptions actuelles à la capacité maximale de l'événement)
        $currentRegistrationsCount = $event->registrations()->count();

        if ($currentRegistrationsCount >= $event->capacity) { // Assure-toi que le champ s'appelle 'capacity' ou 'nombre_de_places' dans ta table events
            return back()->with('error', "Désolé, cet événement est complet !");
        }

        // 3. Vérifier si l'utilisateur n'est pas déjà inscrit avec cet email
        $existingRegistration = $event->registrations()->where('user_email', $request->user_email)->first();
        if ($existingRegistration) {
            return back()->with('error', "Vous êtes déjà inscrit à cet événement avec cet email.");
        }

        // 4. Enregistrer l'inscription
        Registration::create([
            'event_id' => $event->id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
        ]);

        return back()->with('success', "Inscription réussie avec succès !");
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return back()->with('success', "Votre inscription a bien été annulée.");
    }
}
