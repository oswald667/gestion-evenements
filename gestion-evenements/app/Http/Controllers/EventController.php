<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    // ── Liste (accueil) ─────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Event::query()->orderBy('event_date');

        // Filtre recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre date
        if ($request->filled('date_filter')) {
            $today = now()->startOfDay();
            match ($request->date_filter) {
                'today'    => $query->whereDate('event_date', today()),
                'week'     => $query->whereBetween('event_date', [$today, $today->copy()->addWeek()]),
                'month'    => $query->whereBetween('event_date', [$today, $today->copy()->addMonth()]),
                default    => null,
            };
        }

        $events     = $query->paginate(9)->withQueryString();
        $categories = ['Conférence', 'Concert', 'Sport', 'Atelier', 'Exposition', 'Autre'];
        $totalCount = Event::count();

        return view('events.index', compact('events', 'categories', 'totalCount'));
    }

    // ── Détail ──────────────────────────────────────────────
    public function show(Event $event): View
    {
        $event->load('registrations');
        return view('events.show', compact('event'));
    }

    // ── Formulaire création ─────────────────────────────────
    public function create(): View
    {
        $categories = ['Conférence', 'Concert', 'Sport', 'Atelier', 'Exposition', 'Autre'];
        return view('events.create', compact('categories'));
    }

    // ── Enregistrer ─────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'event_date'       => 'required|date|after:now',
            'location'         => 'required|string|max:255',
            'category'         => 'required|in:Conférence,Concert,Sport,Atelier,Exposition,Autre',
            'total_places'     => 'required|integer|min:1',
            'image'            => 'nullable|image|max:2048',
        ]);

        $validated['available_places'] = $validated['total_places'];

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Événement "' . $event->title . '" créé avec succès !');
    }

    // ── Formulaire édition ──────────────────────────────────
    public function edit(Event $event): View
    {
        $categories = ['Conférence', 'Concert', 'Sport', 'Atelier', 'Exposition', 'Autre'];
        return view('events.edit', compact('event', 'categories'));
    }

    // ── Mettre à jour ───────────────────────────────────────
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'event_date'   => 'required|date',
            'location'     => 'required|string|max:255',
            'category'     => 'required|in:Conférence,Concert,Sport,Atelier,Exposition,Autre',
            'total_places' => 'required|integer|min:1',
            'image'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        // Recalculer les places disponibles si le total change
        $diff = $validated['total_places'] - $event->total_places;
        $validated['available_places'] = max(0, $event->available_places + $diff);

        $event->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Événement mis à jour avec succès.');
    }

    // ── Supprimer ───────────────────────────────────────────
    public function destroy(Event $event): RedirectResponse
    {
        $title = $event->title;
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', '"' . $title . '" a été supprimé.');
    }
}