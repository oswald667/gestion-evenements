@extends('layouts.app')

@section('title', 'Découvrir les événements — EventHub')

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

            {{-- Texte --}}
            <div class="max-w-xl">
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-widest mb-2">
                    Agenda Officiel des Événements 2025
                </p>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 leading-tight mb-3">
                    Trouvez et participez aux<br>
                    <span class="text-indigo-600 underline decoration-wavy decoration-indigo-200">
                        meilleurs événements
                    </span>
                </h1>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">
                    Explorez des événements tech, culture, sport et bien plus.
                    Inscriptions simplifiées et confirmation instantanée.
                </p>
                <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-green-500"></i>Accès immédiat
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-amber-500"></i>Validation en 1 clic
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-ban text-indigo-400"></i>Zéro coordonnées bancaires
                    </span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex gap-4 flex-shrink-0">
                <div class="bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-center">
                    <p class="text-2xl font-extrabold text-gray-900">{{ $totalCount }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Événements actifs</p>
                    <p class="text-xs text-green-600 font-medium mt-1">
                        <i class="fa-solid fa-arrow-trend-up mr-1"></i>En ligne
                    </p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-center">
                    <i class="fa-solid fa-ticket text-indigo-400 text-xl mb-1 block"></i>
                    <p class="text-xs text-gray-500">Mode Démo</p>
                    <p class="text-xs text-indigo-600 font-medium mt-1">Gratuit</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FILTRES ─────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-gray-100 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <form method="GET" action="{{ route('events.index') }}"
              class="flex flex-wrap items-center gap-3">

            {{-- Recherche --}}
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2
                           text-gray-400 text-sm pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par titre, lieu…"
                       class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200
                              rounded-xl focus:outline-none focus:bg-white focus:border-indigo-300
                              focus:ring-2 focus:ring-indigo-100" />
            </div>

            {{-- Catégorie --}}
            <select name="category"
                    class="text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2
                           focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100
                           text-gray-600">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            {{-- Filtre date --}}
            <select name="date_filter"
                    class="text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2
                           focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100
                           text-gray-600">
                <option value="">Toutes les dates</option>
                <option value="today"  {{ request('date_filter') === 'today'  ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week"   {{ request('date_filter') === 'week'   ? 'selected' : '' }}>Cette semaine</option>
                <option value="month"  {{ request('date_filter') === 'month'  ? 'selected' : '' }}>Ce mois</option>
            </select>

            {{-- Boutons --}}
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                           px-5 py-2 rounded-xl transition-colors shadow-sm">
                <i class="fa-solid fa-filter mr-1.5"></i>Filtrer
            </button>

            @if(request()->hasAny(['search','category','date_filter']))
            <a href="{{ route('events.index') }}"
               class="text-sm text-gray-500 hover:text-indigo-600 transition-colors px-2">
                <i class="fa-solid fa-rotate-left mr-1"></i>Réinitialiser
            </a>
            @endif
        </form>
    </div>
</section>

{{-- ── GRILLE D'ÉVÉNEMENTS ──────────────────────────────────────── --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if($events->isEmpty())
    <div class="text-center py-20">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-calendar-xmark text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun événement trouvé</h3>
        <p class="text-sm text-gray-400 mb-4">Essayez de modifier vos filtres.</p>
        <a href="{{ route('events.index') }}"
           class="text-sm text-indigo-600 hover:underline font-medium">
            Voir tous les événements →
        </a>
    </div>
    @else

    {{-- Compteur --}}
    <p class="text-sm text-gray-500 mb-6">
        Affichage de
        <span class="font-semibold text-gray-700">{{ $events->firstItem() }}–{{ $events->lastItem() }}</span>
        sur <span class="font-semibold text-gray-700">{{ $events->total() }}</span> événements
    </p>

    {{-- Grille 3 colonnes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <a href="{{ route('events.show', $event) }}"
           class="group bg-white rounded-2xl border border-gray-200 overflow-hidden
                  hover:shadow-lg hover:border-indigo-200 transition-all duration-200 flex flex-col">

            {{-- Image / placeholder --}}
            <div class="relative h-44 bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                @else
                    <div class="w-full h-full flex items-center justify-center opacity-30">
                        <i class="fa-solid fa-calendar-star text-white text-5xl"></i>
                    </div>
                @endif

                {{-- Badge date --}}
                <div class="absolute top-3 right-3 bg-white rounded-xl shadow-md
                            text-center px-2.5 py-1.5 leading-none">
                    <p class="text-xs font-bold text-indigo-600 uppercase">
                        {{ $event->event_date->translatedFormat('M') }}
                    </p>
                    <p class="text-xl font-extrabold text-gray-900 leading-none">
                        {{ $event->event_date->format('d') }}
                    </p>
                </div>

                {{-- Badge catégorie --}}
                <span class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1
                             rounded-full {{ $event->category_color }} backdrop-blur-sm">
                    {{ $event->category }}
                </span>
            </div>

            {{-- Contenu --}}
            <div class="p-4 flex flex-col flex-1">
                <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1
                           group-hover:text-indigo-600 transition-colors line-clamp-2">
                    {{ $event->title }}
                </h3>
                <p class="text-xs text-gray-500 mb-3 line-clamp-2 flex-1">
                    {{ $event->description }}
                </p>

                {{-- Infos --}}
                <div class="space-y-1.5 text-xs text-gray-500 mb-3">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-indigo-400 w-3.5 text-center"></i>
                        {{ $event->event_date->format('H:i') }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-indigo-400 w-3.5 text-center"></i>
                        <span class="truncate">{{ $event->location }}</span>
                    </div>
                </div>

                {{-- Places --}}
                <div class="border-t border-gray-100 pt-3">
                    @if($event->is_full)
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-semibold text-red-600">
                                <i class="fa-solid fa-circle-xmark mr-1"></i>Complet
                            </span>
                            <span class="text-xs text-gray-400">0 / {{ $event->total_places }}</span>
                        </div>
                    @elseif($event->is_almost_full)
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-semibold text-amber-600">
                                <i class="fa-solid fa-fire mr-1"></i>Plus que {{ $event->available_places }} places !
                            </span>
                        </div>
                    @else
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-semibold text-green-600">
                                <i class="fa-solid fa-circle-check mr-1"></i>{{ $event->available_places }} places restantes
                            </span>
                            <span class="text-xs text-gray-400">/ {{ $event->total_places }}</span>
                        </div>
                    @endif
                    <div class="places-bar">
                        <div class="places-bar-fill {{ $event->is_full ? 'bg-red-500' : ($event->is_almost_full ? 'bg-amber-500' : '') }}"
                             style="width: {{ $event->occupancy_percent }}%"></div>
                    </div>
                </div>

                {{-- CTA --}}
                <button class="mt-3 w-full text-center text-xs font-semibold py-2 rounded-xl
                               {{ $event->is_full
                                  ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                  : 'bg-indigo-600 text-white hover:bg-indigo-700 transition-colors' }}">
                    {{ $event->is_full ? 'Complet' : "S'inscrire →" }}
                </button>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10 flex justify-center">
        {{ $events->links() }}
    </div>
    @endif
</section>

{{-- ── COMMENT ÇA MARCHE ───────────────────────────────────────── --}}
<section class="bg-gray-50 border-t border-gray-100 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2 text-center">
            Comment ça fonctionne ?
        </p>
        <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-10">
            Participer n'a jamais été aussi simple
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['1', 'bg-indigo-600', 'Sélectionnez votre événement',
                 'Parcourez la programmation par thématique, lieu ou date.'],
                ['2', 'bg-green-500',  'Validation sans carte',
                 'Cliquez sur "S\'inscrire". Votre place est réservée instantanément.'],
                ['3', 'bg-amber-500',  'Accès direct',
                 'Retrouvez vos inscriptions dans "Mes Inscriptions" à tout moment.'],
            ] as [$num, $color, $title, $desc])
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="w-9 h-9 {{ $color }} rounded-xl flex items-center justify-center
                            text-white font-extrabold text-sm mb-4 shadow-sm">
                    {{ $num }}
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection