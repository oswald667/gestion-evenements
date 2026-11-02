<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Liste des catégories disponibles
     */
    public static array $categories = [
        'Conférence',
        'Concert',
        'Sport',
        'Atelier',
        'Exposition',
        'Autre',
    ];

    /**
     * Afficher la liste des événements avec filtres et recherche
     */
    public function index(Request $request)
    {
        $categories = self::$categories;

        $query = Event::query()
            ->withCount(['activeRegistrations as active_registrations_count'])
            ->filter($request->only(['search', 'category', 'date_filter']));

        // Tri : par défaut, les événements à venir les plus proches d'abord
        $events = $query->orderBy('event_date', 'asc')
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events'      => $events,
            'categories'  => $categories,
            'currentSearch'   => $request->get('search', ''),
            'currentCategory' => $request->get('category', ''),
            'currentDate'     => $request->get('date_filter', ''),
        ]);
    }

    /**
     * Formulaire de création d'un événement
     */
    public function create()
    {
        return view('events.create', [
            'categories' => self::$categories,
        ]);
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|min:3|max:255',
            'description'  => 'required|string|min:10',
            'event_date'   => 'required|date|after_or_equal:today',
            'location'     => 'required|string|max:255',
            'category'     => 'required|string|in:' . implode(',', self::$categories),
            'total_places' => 'required|integer|min:1|max:50000',
            'image'        => 'nullable|url|max:500',
        ], [
            'title.required'        => 'Le titre de l\'événement est obligatoire.',
            'description.required'  => 'La description détaillée est obligatoire.',
            'event_date.required'   => 'La date et l\'heure sont obligatoires.',
            'event_date.after_or_equal' => 'La date de l\'événement ne peut pas être dans le passé.',
            'location.required'     => 'Le lieu de l\'événement est obligatoire.',
            'category.required'     => 'La catégorie est obligatoire.',
            'category.in'           => 'Veuillez sélectionner une catégorie valide.',
            'total_places.required' => 'Le nombre total de places est obligatoire.',
            'total_places.min'      => 'Le nombre de places doit être d\'au moins 1.',
            'image.url'             => 'L\'image doit être une URL valide (http:// ou https://).',
        ]);

        $validated['available_places'] = $validated['total_places'];

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'L\'événement "' . $event->title . '" a été créé avec succès !');
    }

    /**
     * Afficher les détails d'un événement
     */
    public function show(Event $event)
    {
        $event->load(['registrations' => function ($q) {
            $q->latest();
        }]);

        return view('events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Formulaire de modification d'un événement
     */
    public function edit(Event $event)
    {
        return view('events.edit', [
            'event'      => $event,
            'categories' => self::$categories,
        ]);
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'        => 'required|string|min:3|max:255',
            'description'  => 'required|string|min:10',
            'event_date'   => 'required|date',
            'location'     => 'required|string|max:255',
            'category'     => 'required|string|in:' . implode(',', self::$categories),
            'total_places' => 'required|integer|min:1|max:50000',
            'image'        => 'nullable|url|max:500',
        ], [
            'title.required'        => 'Le titre de l\'événement est obligatoire.',
            'description.required'  => 'La description détaillée est obligatoire.',
            'event_date.required'   => 'La date et l\'heure sont obligatoires.',
            'location.required'     => 'Le lieu de l\'événement est obligatoire.',
            'category.required'     => 'La catégorie est obligatoire.',
            'category.in'           => 'Veuillez sélectionner une catégorie valide.',
            'total_places.required' => 'Le nombre total de places est obligatoire.',
            'total_places.min'      => 'Le nombre de places doit être d\'au moins 1.',
            'image.url'             => 'L\'image doit être une URL valide.',
        ]);

        // Vérifier que la nouvelle capacité n'est pas inférieure au nombre d'inscrits actuels
        $activeRegistrationsCount = $event->activeRegistrations()->count();
        if ($validated['total_places'] < $activeRegistrationsCount) {
            return back()->withInput()->with('error', "Le nombre de places ({$validated['total_places']}) ne peut pas être inférieur au nombre d'inscriptions actives actuelles ({$activeRegistrationsCount}).");
        }

        $validated['available_places'] = max(0, $validated['total_places'] - $activeRegistrationsCount);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'L\'événement a été mis à jour avec succès !');
    }

    /**
     * Supprimer un événement
     */
    public function destroy(Event $event)
    {
        $title = $event->title;
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'L\'événement "' . $title . '" et ses inscriptions ont été supprimés.');
    }
}
