<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'EventHub - Gestion des événements')</title>

    <!-- Tailwind CSS (CDN pour démarrage immédiat + Vite si compilé) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        .navbar-shadow { box-shadow: 0 1px 0 #e5e7eb, 0 2px 12px rgba(0,0,0,.04); }
        .badge-demo {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-demo::before {
            content: '';
            display: inline-block;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #16a34a;
            margin-right: 6px;
            animation: pulse-dot 1.6s infinite;
        }
        @keyframes pulse-dot {
            0%,100%{ opacity:1; } 50%{ opacity:.4; }
        }
        @keyframes slideDown {
            from{ opacity:0; transform:translateY(-8px); }
            to  { opacity:1; transform:translateY(0); }
        }
        .flash-in { animation: slideDown .25s ease-out; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col antialiased font-sans">

{{-- ================================================================
     NAVBAR
     ================================================================ --}}
<header class="bg-white navbar-shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16 gap-4 justify-between">

            {{-- Logo --}}
            <a href="{{ route('events.index') }}" class="flex items-center gap-2.5 flex-shrink-0 group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-indigo-600 group-hover:bg-indigo-700 transition-colors shadow-sm text-white">
                    <i class="fa-solid fa-calendar-check text-base"></i>
                </div>
                <span class="text-lg font-bold text-gray-900 tracking-tight leading-none">
                    Event<span class="text-indigo-600">Hub</span>
                </span>
            </a>

            {{-- Barre de recherche globale (desktop) --}}
            <form method="GET" action="{{ route('events.index') }}" class="hidden md:flex flex-1 max-w-sm mx-4">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un événement, lieu..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-xl focus:outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all" />
                </div>
            </form>

            {{-- Navigation desktop --}}
            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('events.index') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ request()->routeIs('events.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-compass mr-1.5 opacity-70"></i> Découvrir
                </a>
                <a href="{{ route('registrations.index') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ request()->routeIs('registrations.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-ticket mr-1.5 opacity-70"></i> Mes Inscriptions
                </a>
                <a href="{{ route('events.create') }}"
                   class="inline-flex items-center gap-1.5 ml-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow transition-all">
                    <i class="fa-solid fa-plus text-xs"></i> Créer un événement
                </a>
            </nav>

            {{-- Bouton mobile --}}
            <div class="md:hidden flex items-center gap-2">
                <a href="{{ route('events.create') }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                    <i class="fa-solid fa-plus text-lg"></i>
                </a>
                <button id="nav-toggle" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none" aria-label="Menu">
                    <i id="burger-icon" class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>

        </div>

        {{-- Menu déroulant mobile --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 py-3 space-y-2">
            <form method="GET" action="{{ route('events.index') }}" class="mb-3 px-1">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-lg" />
                </div>
            </form>
            <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                <i class="fa-solid fa-compass mr-2"></i> Découvrir les événements
            </a>
            <a href="{{ route('registrations.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                <i class="fa-solid fa-ticket mr-2"></i> Mes Inscriptions
            </a>
            <a href="{{ route('events.create') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-indigo-600 bg-indigo-50">
                <i class="fa-solid fa-plus mr-2"></i> Créer un nouvel événement
            </a>
        </div>
    </div>
</header>

{{-- ================================================================
     MESSAGES FLASH
     ================================================================ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
    @if(session('success'))
    <div class="flash-in flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-4 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
        <button onclick="this.closest('[role=alert]').remove()" class="text-emerald-500 hover:text-emerald-800 ml-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="flash-in flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
        <button onclick="this.closest('[role=alert]').remove()" class="text-red-500 hover:text-red-800 ml-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="flash-in flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl mb-4 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('warning') }}</p>
        <button onclick="this.closest('[role=alert]').remove()" class="text-amber-500 hover:text-amber-800 ml-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="flash-in flex items-start gap-3 bg-indigo-50 border border-indigo-200 text-indigo-800 px-4 py-3 rounded-xl mb-4 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('info') }}</p>
        <button onclick="this.closest('[role=alert]').remove()" class="text-indigo-500 hover:text-indigo-800 ml-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
</div>

{{-- ================================================================
     CONTENU PRINCIPAL
     ================================================================ --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ================================================================
     FOOTER
     ================================================================ --}}
<footer class="bg-white border-t border-gray-200 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <i class="fa-solid fa-calendar-check text-sm"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-gray-800">Event<span class="text-indigo-600">Hub</span></span>
                    <span class="text-xs text-gray-400 block sm:inline sm:ml-2">
                        &copy; {{ date('Y') }} EventHub. Tous droits réservés.
                    </span>
                </div>
            </div>

        </div>
    </div>
</footer>

<script>
    const toggle = document.getElementById('nav-toggle');
    const menu   = document.getElementById('mobile-menu');
    const icon   = document.getElementById('burger-icon');

    if (toggle && menu && icon) {
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('hidden');
            icon.className = open ? 'fa-solid fa-bars text-lg' : 'fa-solid fa-xmark text-lg';
        });
    }

    setTimeout(() => {
        document.querySelectorAll('[role="alert"]').forEach(el => {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
