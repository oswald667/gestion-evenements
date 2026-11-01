@php $isEdit = isset($event) && $event->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Titre --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Titre de l'événement <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title"
               value="{{ old('title', $isEdit ? $event->title : '') }}"
               placeholder="Ex: Conférence Tech Africa 2026"
               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                      focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                      @error('title') border-red-400 bg-red-50 @enderror" />
        @error('title')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Date --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Date & heure <span class="text-red-500">*</span>
        </label>
        <input type="datetime-local" name="event_date"
               value="{{ old('event_date', $isEdit ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                      focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                      @error('event_date') border-red-400 bg-red-50 @enderror" />
        @error('event_date')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Catégorie --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Catégorie <span class="text-red-500">*</span>
        </label>
        <select name="category"
                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                       focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                       @error('category') border-red-400 bg-red-50 @enderror">
            <option value="">Choisir une catégorie</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}"
                    {{ old('category', $isEdit ? $event->category : '') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
        @error('category')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Lieu --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Lieu <span class="text-red-500">*</span>
        </label>
        <input type="text" name="location"
               value="{{ old('location', $isEdit ? $event->location : '') }}"
               placeholder="Ex: Palais des Congrès, Cotonou"
               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                      focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                      @error('location') border-red-400 bg-red-50 @enderror" />
        @error('location')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Nombre de places --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Nombre de places <span class="text-red-500">*</span>
        </label>
        <input type="number" name="total_places" min="1"
               value="{{ old('total_places', $isEdit ? $event->total_places : '') }}"
               placeholder="Ex: 150"
               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                      focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                      @error('total_places') border-red-400 bg-red-50 @enderror" />
        @error('total_places')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Description <span class="text-red-500">*</span>
        </label>
        <textarea name="description" rows="4"
                  placeholder="Décrivez l'événement…"
                  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
                         focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100
                         resize-none @error('description') border-red-400 bg-red-50 @enderror">{{ old('description', $isEdit ? $event->description : '') }}</textarea>
        @error('description')
            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Image --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Image de couverture <span class="text-xs text-gray-400 font-normal">(optionnel)</span>
        </label>
        <input type="file" name="image" accept="image/*"
               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                      file:rounded-xl file:border-0 file:text-sm file:font-semibold
                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
        @error('image')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>