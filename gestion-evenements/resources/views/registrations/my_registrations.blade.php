@extends('layouts.app')

@section('title', 'Mes Inscriptions - EventHub')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
                Gestion des Inscriptions
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Consultez l'historique de vos réservations et annulez une inscription si vous ne pouvez plus participer.
            </p>
        </div>

        <a href="{{ route('events.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors self-start md:self-auto">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>S'inscrire à un autre événement</span>
        </a>
    </div>

    {{-- Barre de recherche par email --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm mb-8">
        <form method="GET" action="{{ route('registrations.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="email" value="{{ $email }}"
                       placeholder="Filtrer par adresse email (ex: koffi.mensah@gmail.com)..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-600 transition-all" />
            </div>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Rechercher</span>
            </button>

            @if($email)
                <a href="{{ route('registrations.index') }}"
                   class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    <span>Effacer</span>
                </a>
            @endif
        </form>
    </div>

    {{-- Liste des Inscriptions --}}
    @if($registrations->count() > 0)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-bold text-gray-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Événement</th>
                            <th class="px-6 py-4">Participant</th>
                            <th class="px-6 py-4">Date de réservation</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($registrations as $reg)
                            @php
                                $event = $reg->event;
                                $isActive = $reg->status === 'active';
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                
                                {{-- Événement --}}
                                <td class="px-6 py-4">
                                    @if($event)
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 text-base">
                                                <i class="fa-solid fa-calendar-day"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('events.show', $event) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition-colors line-clamp-1">
                                                    {{ $event->title }}
                                                </a>
                                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                                    <span>{{ $event->category }}</span>
                                                    <span>•</span>
                                                    <span>{{ $event->event_date->translatedFormat('d M Y à H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Événement supprimé</span>
                                    @endif
                                </td>

                                {{-- Participant --}}
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $reg->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $reg->email }}</div>
                                    @if($reg->phone)
                                        <div class="text-xs text-gray-400">{{ $reg->phone }}</div>
                                    @endif
                                </td>

                                {{-- Date d'inscription --}}
                                <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $reg->created_at->translatedFormat('d/m/Y à H:i') }}
                                </td>

                                {{-- Statut --}}
                                <td class="px-6 py-4">
                                    @if($isActive)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Annulée
                                        </span>
                                    @endif
                                </td>

                                {{-- Action Annuler --}}
                                <td class="px-6 py-4 text-right">
                                    @if($isActive)
                                        <form action="{{ route('registrations.destroy', $reg) }}" method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette inscription ? La place sera libérée pour d\'autres participants.');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                                <i class="fa-solid fa-ban text-[11px]"></i>
                                                <span>Annuler l'inscription</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Aucune action</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-100">
                {{ $registrations->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center max-w-md mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-ticket-simple"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune inscription trouvée</h3>
            <p class="text-sm text-gray-500 mb-6">
                @if($email)
                    Aucune réservation n'a été trouvée pour l'email <span class="font-semibold text-gray-800">{{ $email }}</span>.
                @else
                    Aucune inscription enregistrée pour le moment.
                @endif
            </p>
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                <i class="fa-solid fa-compass"></i>
                <span>Parcourir les événements</span>
            </a>
        </div>
    @endif

</div>
@endsection
