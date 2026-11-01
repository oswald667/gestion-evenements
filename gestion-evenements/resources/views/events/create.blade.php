@extends('layouts.app')
@section('title', 'Créer un événement — EventHub')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Créer un événement</h1>
        <p class="text-sm text-gray-500 mt-1">Remplissez les informations pour publier votre événement.</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('events.partials.form')
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('events.index') }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                               px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                    <i class="fa-solid fa-plus mr-1.5"></i>Publier l'événement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection