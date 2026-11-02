@extends('layouts.app')

@section('title', 'Découvrir les événements - EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-950 text-white p-8 md:p-12 mb-10 shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Plateforme officielle IFRI
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4">
                Explorez les événements à ne pas manquer
            </h1>
            <p class="text-indigo-200 text-base md:text-lg mb-6">
                Conférences, ateliers techniques, concerts et compétitions sportives au Bénin. Consultez les détails et réservez votre place en quelques secondes.
            </p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="#events-list" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-indigo-900 bg-white hover:bg-indigo-50 transition-colors shadow">
                    <i class="fa-solid fa-list-ul"></i> Parcourir les événements
                </a>
                <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 border border-indigo-400/40 transition-colors">
                    <i class="fa-solid fa-plus"></i> Publier un événement
                </a>
            </div>
        </div>
        <div class="absolute -right-12 -bottom-12 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    {{-- Filtres et Barre de recherche --}}
    <div id="events-list" class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm mb-8">
        <form method="GET" action="{{ route('events.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                {{-- Recherche texte --}}
                <div class="md:col-span-5 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search" value="{{ $currentSearch }}"
                           placeholder="Rechercher par titre, description, lieu..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all" />
                </div>

                {{-- Filtre par catégorie --}}
                <div class="md:col-span-4">
                    <select name="category" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $currentCategory === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtre par date --}}
                <div class="md:col-span-3">
                    <select name="date_filter" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        <option value="">Toutes les dates</option>
                        <option value="upcoming" {{ $currentDate === 'upcoming' ? 'selected' : '' }}>Événements à venir</option>
                        <option value="today" {{ $currentDate === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="past" {{ $currentDate === 'past' ? 'selected' : '' }}>Événements passés</option>
                    </select>
                </div>
            </div>

            {{-- Boutons rapides de catégories --}}
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
                <span class="text-xs font-semibold text-gray-500 mr-1">Filtre rapide :</span>
                <a href="{{ route('events.index') }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                          {{ empty($currentCategory) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Tous ({{ \App\Models\Event::count() }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('events.index', array_merge(request()->except('page'), ['category' => $cat])) }}"
                       class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                              {{ $currentCategory === $cat ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach

                @if($currentSearch || $currentCategory || $currentDate)
                    <a href="{{ route('events.index') }}" class="ml-auto text-xs text-red-600 hover:text-red-800 font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-xmark"></i> Réinitialiser les filtres
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Liste des Événements --}}
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($events as $event)
                @php
                    $isFull = $event->isFull();
                    $isPast = $event->isPast();
                    $remaining = $event->remaining_places;
                    $percentUsed = $event->total_places > 0 ? min(100, round((($event->total_places - $remaining) / $event->total_places) * 100)) : 100;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col group">
                    
                    {{-- Image & Badge Catégorie --}}
                    <div class="relative h-48 w-full bg-indigo-900 overflow-hidden">
                        @if($event->image)
                            <img src="{{ $event->image }}" alt="{{ $event->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60';" />
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-indigo-700 to-indigo-900 text-indigo-300">
                                <i class="fa-solid fa-calendar-days text-5xl opacity-40"></i>
                            </div>
                        @endif

                        {{-- Badge Catégorie --}}
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/90 text-indigo-900 backdrop-blur-sm shadow">
                            {{ $event->category }}
                        </span>

                        {{-- Statut Complet / Passé --}}
                        @if($isPast)
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-900/80 text-white backdrop-blur-sm">
                                <i class="fa-solid fa-clock-rotate-left mr-1"></i> Passé
                            </span>
                        @elseif($isFull)
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-600/90 text-white backdrop-blur-sm">
                                <i class="fa-solid fa-ban mr-1"></i> Complet
                            </span>
                        @endif
                    </div>

                    {{-- Corps de la Carte --}}
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            {{-- Date & Lieu --}}
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">
                                <span class="inline-flex items-center gap-1 font-semibold text-indigo-600">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $event->event_date->translatedFormat('d M Y à H:i') }}
                                </span>
                                <span>•</span>
                                <span class="inline-flex items-center gap-1 truncate" title="{{ $event->location }}">
                                    <i class="fa-solid fa-location-dot text-gray-400"></i>
                                    {{ Str::limit($event->location, 22) }}
                                </span>
                            </div>

                            {{-- Titre --}}
                            <h2 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                                <a href="{{ route('events.show', $event) }}">
                                    {{ $event->title }}
                                </a>
                            </h2>

                            {{-- Description --}}
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                {{ $event->description }}
                            </p>
                        </div>

                        {{-- Jauge des Places Restantes --}}
                        <div class="pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs font-medium mb-1.5">
                                <span class="text-gray-500">Places restantes</span>
                                @if($remaining <= 0)
                                    <span class="text-red-600 font-bold">0 / {{ $event->total_places }}</span>
                                @elseif($remaining <= 10)
                                    <span class="text-amber-600 font-bold">{{ $remaining }} restantes (Bientôt complet)</span>
                                @else
                                    <span class="text-emerald-600 font-bold">{{ $remaining }} sur {{ $event->total_places }}</span>
                                @endif
                            </div>
                            
                            {{-- Barre de progression --}}
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden mb-4">
                                <div class="h-2 rounded-full transition-all duration-500
                                    {{ $remaining <= 0 ? 'bg-red-500' : ($remaining <= 10 ? 'bg-amber-500' : 'bg-indigo-600') }}"
                                     style="width: {{ $percentUsed }}%"></div>
                            </div>

                            {{-- Bouton d'action --}}
                            <a href="{{ route('events.show', $event) }}"
                               class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-center flex items-center justify-center gap-2 transition-all
                                      {{ $isPast ? 'bg-gray-100 text-gray-500 hover:bg-gray-200' : ($isFull ? 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm') }}">
                                <span>Voir le détail</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        {{-- État vide compréhensible --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center max-w-lg mx-auto shadow-sm my-12">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun événement trouvé</h3>
            <p class="text-sm text-gray-500 mb-6">
                Aucun événement ne correspond à vos critères de recherche actuels. Essayez de modifier vos filtres ou de créer un nouvel événement.
            </p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('events.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Effacer les filtres
                </a>
                <a href="{{ route('events.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow">
                    Créer un événement
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
