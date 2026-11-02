@extends('layouts.app')

@section('title', 'Modifier l\'événement : ' . $event->title . ' - EventHub')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Fil d'ariane --}}
    <div class="mb-6">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Retour aux détails de l'événement</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-10 shadow-sm">
        <div class="mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-700 mb-2">
                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Modification
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Modifier l'événement
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Mettez à jour les informations relatives à cet événement.
            </p>
        </div>

        {{-- Affichage des erreurs globales si formulaire invalide --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    <span>Certains champs comportent des erreurs :</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-red-700 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Titre --}}
            <div>
                <label for="title" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                    Titre de l'événement <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $event->title) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('title') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                @error('title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catégorie & Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="category" required
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('category') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $event->category) === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="event_date" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Date et Heure <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="event_date" id="event_date"
                           value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('event_date') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                    @error('event_date')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Lieu & Total Places --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="location" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Lieu de déroulement <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location"
                           value="{{ old('location', $event->location) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('location') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                    @error('location')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_places" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Nombre de places <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_places" id="total_places"
                           value="{{ old('total_places', $event->total_places) }}" min="1" max="50000" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('total_places') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                    <p class="text-[10px] text-gray-400 mt-1">Actuellement {{ $event->activeRegistrations()->count() }} inscrit(s)</p>
                    @error('total_places')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image URL (Optionnel) --}}
            <div>
                <label for="image" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                    Image d'illustration (URL) <span class="text-gray-400 text-[10px] font-normal">(Optionnel)</span>
                </label>
                <input type="url" name="image" id="image"
                       value="{{ old('image', $event->image) }}"
                       placeholder="https://..."
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('image') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
                @error('image')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description détaillée --}}
            <div>
                <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                    Description complète de l'événement <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="5" required
                          class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Boutons d'action --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('events.show', $event) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Mettre à jour l'événement</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
