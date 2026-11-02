@extends('layouts.app')

@section('title', $event->title . ' - EventHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Fil d'ariane & Bouton Retour --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Retour aux événements</span>
        </a>

        {{-- Actions d'administration CRUD (Modifier / Supprimer) --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 shadow-sm transition-colors">
                <i class="fa-solid fa-pen-to-square text-indigo-600"></i> Modifier
            </a>
            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Toutes les inscriptions associées seront supprimées.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 transition-colors">
                    <i class="fa-solid fa-trash-can"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    @php
        $isFull = $event->isFull();
        $isPast = $event->isPast();
        $remaining = $event->remaining_places;
        $percentUsed = $event->total_places > 0 ? min(100, round((($event->total_places - $remaining) / $event->total_places) * 100)) : 100;
        $activeRegistrations = $event->registrations->where('status', 'active');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Colonne Principale : Détails de l'événement --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Image & Entête --}}
            <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
                @if($event->image)
                    <div class="h-80 w-full relative overflow-hidden bg-gray-900">
                        <img src="{{ $event->image }}" alt="{{ $event->title }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60';" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <span class="absolute top-4 left-4 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white text-indigo-900 shadow">
                            {{ $event->category }}
                        </span>
                    </div>
                @endif

                <div class="p-6 md:p-8">
                    @if(!$event->image)
                        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 mb-3">
                            {{ $event->category }}
                        </span>
                    @endif

                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
                        {{ $event->title }}
                    </h1>

                    {{-- Badges Date, Heure, Lieu --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Date & Heure</p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $event->event_date->translatedFormat('l d F Y à H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Lieu</p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $event->location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Description Complète --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-3">À propos de cet événement</h2>
                        <div class="text-gray-700 leading-relaxed text-sm md:text-base space-y-4 whitespace-pre-line">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Liste des participants déjà inscrits (Démonstration & Transparence) --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-6 md:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Participants inscrits ({{ $activeRegistrations->count() }})
                        </h3>
                        <p class="text-xs text-gray-500">Inscriptions enregistrées en base de données</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                        {{ $activeRegistrations->count() }} / {{ $event->total_places }} places prises
                    </span>
                </div>

                @if($activeRegistrations->count() > 0)
                    <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                        @foreach($activeRegistrations as $reg)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($reg->full_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $reg->full_name }}</p>
                                        <p class="text-xs text-gray-400">Inscrit le {{ $reg->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>

                                {{-- Bouton Annuler l'inscription --}}
                                <form action="{{ route('registrations.destroy', $reg) }}" method="POST" onsubmit="return confirm('Annuler l\'inscription de {{ $reg->full_name }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline px-2 py-1 rounded hover:bg-red-50">
                                        Annuler
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-user-plus text-3xl mb-2 opacity-30"></i>
                        <p>Aucun participant n'est encore inscrit. Soyez le premier !</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Colonne Latérale : Formulaire d'Inscription & Jauge --}}
        <div class="lg:col-span-4">
            <div class="sticky top-24 space-y-6">

                <div class="bg-white rounded-3xl border border-gray-200 p-6 md:p-7 shadow-lg">
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Inscription</h3>
                    <p class="text-xs text-gray-500 mb-5">
                        Simulation gratuite sans paiement ni envoi d'email
                    </p>

                    {{-- Jauge des places --}}
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 mb-6">
                        <div class="flex items-center justify-between text-xs font-semibold mb-2">
                            <span class="text-gray-600">Disponibilité</span>
                            @if($isPast)
                                <span class="text-gray-500 font-bold">Événement terminé</span>
                            @elseif($isFull)
                                <span class="text-red-600 font-bold">Complet (0 restante)</span>
                            @else
                                <span class="text-emerald-700 font-bold">{{ $remaining }} places restantes</span>
                            @endif
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden mb-2">
                            <div class="h-2.5 rounded-full transition-all duration-500
                                {{ $remaining <= 0 ? 'bg-red-500' : ($remaining <= 10 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                 style="width: {{ $percentUsed }}%"></div>
                        </div>

                        <div class="flex justify-between text-[11px] text-gray-400">
                            <span>0</span>
                            <span>Capacité : {{ $event->total_places }} pers.</span>
                        </div>
                    </div>

                    {{-- Formulaire si places dispos et non passé --}}
                    @if(!$isPast && !$isFull)
                        <form action="{{ route('registrations.store', $event) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="full_name" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name"
                                       value="{{ old('full_name') }}" required
                                       placeholder="Ex: Koffi Mensah"
                                       class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('full_name') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                                @error('full_name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                                    Adresse Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email') }}" required
                                       placeholder="Ex: koffi.mensah@gmail.com"
                                       class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                                    Téléphone <span class="text-gray-400 text-[10px]">(Optionnel)</span>
                                </label>
                                <input type="text" name="phone" id="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="Ex: +229 97 00 11 22"
                                       class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                                @error('phone')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-ticket"></i>
                                <span>Confirmer l'inscription</span>
                            </button>
                        </form>
                    @elseif($isPast)
                        <div class="p-4 rounded-2xl bg-gray-100 text-center text-gray-600 text-sm">
                            <i class="fa-solid fa-calendar-xmark text-2xl text-gray-400 mb-2"></i>
                            <p class="font-bold">Inscriptions closes</p>
                            <p class="text-xs text-gray-500 mt-1">Cet événement a déjà eu lieu.</p>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-red-50 text-center text-red-700 text-sm border border-red-200">
                            <i class="fa-solid fa-ban text-2xl text-red-500 mb-2"></i>
                            <p class="font-bold">Événement Complet</p>
                            <p class="text-xs text-red-600 mt-1">Toutes les places ont été réservées.</p>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <a href="{{ route('registrations.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                            Consulter ou annuler une autre inscription
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>
@endsection
