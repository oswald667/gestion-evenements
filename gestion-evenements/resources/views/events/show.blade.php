@extends('layouts.app')

@section('title', $event->title . ' — EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Fil d'Ariane --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Accueil</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('events.index') }}" class="hover:text-indigo-600 transition-colors">Événements</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-gray-600 truncate max-w-[200px]">{{ $event->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── COLONNE PRINCIPALE ────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Image + badges --}}
            <div class="relative rounded-2xl overflow-hidden h-64 md:h-80
                        bg-gradient-to-br from-indigo-500 to-purple-600">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover" />
                @else
                    <div class="w-full h-full flex items-center justify-center opacity-20">
                        <i class="fa-solid fa-calendar-star text-white text-7xl"></i>
                    </div>
                @endif
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                                 {{ $event->category_color }}">
                        {{ $event->category }}
                    </span>
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                                 bg-green-100 text-green-700">
                        <i class="fa-solid fa-circle mr-1 animate-pulse"></i>En présentiel
                    </span>
                </div>
            </div>

            {{-- Titre --}}
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 leading-tight">
                    {{ $event->title }}
                </h1>

                {{-- Méta-infos --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div class="flex items-start gap-2.5 bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <i class="fa-regular fa-calendar text-indigo-500 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs text-gray-400">Date & heure</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $event->event_date->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $event->event_date->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5 bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <i class="fa-solid fa-location-dot text-indigo-500 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs text-gray-400">Lieu</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $event->location }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5 bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <i class="fa-solid fa-users text-indigo-500 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs text-gray-400">Places</p>
                            <p class="text-sm font-semibold
                                      {{ $event->is_full ? 'text-red-600' : 'text-green-600' }}">
                                {{ $event->available_places }} disponibles
                            </p>
                            <p class="text-xs text-gray-500">sur {{ $event->total_places }}</p>
                        </div>
                    </div>
                </div>

                {{-- Jauge --}}
                @if($event->is_almost_full && !$event->is_full)
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center
                            gap-3 mb-5">
                    <i class="fa-solid fa-fire text-amber-500"></i>
                    <p class="text-sm font-medium text-amber-700">
                        Forte demande — il ne reste que
                        <strong>{{ $event->available_places }} places</strong>
                        sur {{ $event->total_places }}
                    </p>
                </div>
                @endif
                <div class="places-bar mb-6">
                    <div class="places-bar-fill
                                {{ $event->is_full ? 'bg-red-500' : ($event->is_almost_full ? 'bg-amber-500' : '') }}"
                         style="width: {{ $event->occupancy_percent }}%"></div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <h2 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-1 h-4 bg-indigo-600 rounded-full inline-block"></span>
                    À propos de l'événement
                </h2>
                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>
        </div>

        {{-- ── SIDEBAR ────────────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Carte inscription --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm sticky top-24">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                        Accès à l'événement
                    </p>
                    <span class="text-xs font-bold text-green-600 bg-green-50 border border-green-200
                                 px-2.5 py-1 rounded-full">
                        100% GRATUIT
                    </span>
                </div>

                {{-- Prix --}}
                <div class="mb-4">
                    <span class="text-4xl font-extrabold text-gray-900">0 €</span>
                    <span class="text-sm text-gray-400 ml-1">/ participant</span>
                    <p class="text-xs text-gray-400 mt-1">Mode Simulation — sans paiement réel</p>
                </div>

                {{-- Alerte places --}}
                @if($event->is_almost_full && !$event->is_full)
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 mb-4
                            flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                    <p class="text-xs font-semibold text-amber-700">
                        Plus que {{ $event->available_places }} places disponibles !
                    </p>
                </div>
                @endif

                {{-- Places restantes --}}
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                        <span>Nombre de places</span>
                        <span class="font-semibold">
                            {{ $event->available_places }} / {{ $event->total_places }}
                        </span>
                    </div>
                    <div class="places-bar">
                        <div class="places-bar-fill
                                    {{ $event->is_full ? 'bg-red-500' : ($event->is_almost_full ? 'bg-amber-500' : '') }}"
                             style="width: {{ $event->occupancy_percent }}%"></div>
                    </div>
                </div>

                {{-- Bouton inscription --}}
                @if($event->is_full)
                    <button disabled
                            class="w-full py-3 rounded-xl bg-gray-100 text-gray-400
                                   text-sm font-semibold cursor-not-allowed">
                        <i class="fa-solid fa-circle-xmark mr-2"></i>Événement complet
                    </button>
                @else
                    <a href="{{ route('registrations.create', $event) }}"
                       class="block w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700
                              text-white text-sm font-semibold text-center transition-colors shadow-sm">
                        <i class="fa-solid fa-bolt mr-2"></i>S'inscrire immédiatement (Gratuit)
                    </a>
                @endif

                {{-- Inclus --}}
                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        CE QUI EST INCLUS
                    </p>
                    @foreach([
                        'Inscription simulée sans paiement',
                        'Annulation possible à tout moment',
                        'Places mises à jour en temps réel',
                    ] as $item)
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <i class="fa-solid fa-check text-green-500 flex-shrink-0"></i>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions admin --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">
                    Gestion
                </p>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('events.edit', $event) }}"
                       class="flex items-center gap-2 text-sm font-medium text-gray-700
                              hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-xl transition-colors">
                        <i class="fa-solid fa-pen-to-square text-indigo-400 w-4 text-center"></i>
                        Modifier l'événement
                    </a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST"
                          onsubmit="return confirm('Supprimer cet événement ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center gap-2 text-sm font-medium text-red-600
                                       hover:bg-red-50 px-3 py-2 rounded-xl transition-colors">
                            <i class="fa-solid fa-trash-can w-4 text-center"></i>
                            Supprimer l'événement
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection