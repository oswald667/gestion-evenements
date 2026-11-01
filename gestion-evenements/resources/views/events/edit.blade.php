@extends('layouts.app')
@section('title', 'Modifier — ' . $event->title)
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Modifier l'événement</h1>
        <p class="text-sm text-gray-500 mt-1 truncate">{{ $event->title }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('events.partials.form')
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('events.show', $event) }}"
                   class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                               px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection