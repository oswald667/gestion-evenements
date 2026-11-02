<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Page "Mes Inscriptions" : consultation et recherche par email
     */
    public function myRegistrations(Request $request)
    {
        $email = $request->query('email');

        $registrations = Registration::with('event')
            ->when($email, function ($q) use ($email) {
                $q->where('email', 'like', "%{$email}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('registrations.my_registrations', compact('registrations', 'email'));
    }

    /**
     * Enregistrer une nouvelle inscription à un événement
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'full_name' => 'required|string|min:3|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:30',
        ], [
            'full_name.required' => 'Le nom complet est obligatoire.',
            'full_name.min'      => 'Le nom doit comporter au moins 3 caractères.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'Veuillez saisir une adresse email valide.',
        ]);

        // 2. Vérifier si l'événement est passé
        if ($event->event_date->isPast()) {
            return back()->withInput()->with('error', 'Impossible de s\'inscrire : cet événement est déjà passé.');
        }

        // 3. Vérifier le nombre de places restantes
        if ($event->remaining_places <= 0) {
            return back()->withInput()->with('error', 'Désolé, cet événement est complet ! Il n\'y a plus de places disponibles.');
        }

        // 4. Vérifier si l'utilisateur est déjà activement inscrit
        $existingActive = $event->registrations()
            ->where('email', $validated['email'])
            ->where('status', 'active')
            ->first();

        if ($existingActive) {
            return back()->withInput()->with('warning', 'Vous êtes déjà inscrit à cet événement avec cette adresse email.');
        }

        // 5. Si une ancienne inscription annulée existe, on la réactive ; sinon on crée
        $existingCancelled = $event->registrations()
            ->where('email', $validated['email'])
            ->where('status', 'cancelled')
            ->first();

        if ($existingCancelled) {
            $existingCancelled->update([
                'full_name' => $validated['full_name'],
                'phone'     => $validated['phone'] ?? null,
                'status'    => 'active',
            ]);
        } else {
            $event->registrations()->create([
                'full_name' => $validated['full_name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'] ?? null,
                'status'    => 'active',
            ]);
        }

        // 6. Synchroniser available_places
        $event->update([
            'available_places' => $event->remaining_places,
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Félicitations ! Votre inscription a été enregistrée avec succès. (Simulation gratuite sans paiement)');
    }

    /**
     * Annuler une inscription
     */
    public function destroy(Registration $registration)
    {
        $event = $registration->event;

        // Marquer comme annulée
        $registration->update(['status' => 'cancelled']);

        // Recalculer les places restantes de l'événement
        if ($event) {
            $event->update([
                'available_places' => $event->remaining_places,
            ]);
        }

        return back()->with('success', 'L\'inscription de ' . $registration->full_name . ' a bien été annulée. La place a été libérée.');
    }
}
