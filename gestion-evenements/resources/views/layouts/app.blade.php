<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'EventHub')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        :root {
            --brand:   #4f46e5;
            --brand-2: #6d28d9;
            --green:   #16a34a;
            --red:     #dc2626;
            --amber:   #d97706;
        }

        /* ---- navbar shadow subtile ---- */
        .navbar-shadow { box-shadow: 0 1px 0 #e5e7eb, 0 2px 12px rgba(0,0,0,.04); }

        /* ---- pill "Mode Démo" ---- */
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

        /* ---- flash slide-down ---- */
        @keyframes slideDown {
            from{ opacity:0; transform:translateY(-8px); }
            to  { opacity:1; transform:translateY(0);    }
        }
        .flash-in { animation: slideDown .25s ease-out; }

        /* ---- barre de places (progress) ---- */
        .places-bar {
            height: 4px;
            border-radius: 9999px;
            background: #e5e7eb;
            overflow: hidden;
        }
        .places-bar-fill {
            height: 100%;
            border-radius: 9999px;
            background: var(--brand);
            transition: width .4s ease;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col antialiased">

{{-- ================================================================
     NAVBAR — fidèle au Stitch EventHub
     ================================================================ --}}
<header class="bg-white navbar-shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 flex-shrink-0 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                            bg-indigo-600 group-hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class="fa-solid fa-calendar-check text-white text-sm"></i>
                </div>
                <span class="text-[17px] font-bold text-gray-900 tracking-tight hidden sm:block">
                    Event<span class="text-indigo-600">Hub</span>
                </span>
            </a>

            {{-- Barre de recherche (desktop) --}}
            <form method="GET" action="{{ url('/events') }}"
                  class="hidden md:flex flex-1 max-w-sm">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2
                               text-gray-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un événement…"
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-100 border border-transparent
                                  rounded-xl focus:outline-none focus:bg-white focus:border-indigo-300
                                  focus:ring-2 focus:ring-indigo-100 transition-all" />
                </div>
            </form>

            {{-- Navigation desktop --}}
            <nav class="hidden md:flex items-center gap-1 ml-auto">
                <a href="{{ url('/events') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->is('events*') && !request()->is('events/*/register')
                             ? 'text-indigo-600 bg-indigo-50'
                             : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    Découvrir
                </a>
                <a href="{{ url('/my-registrations') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->is('my-registrations*')
                             ? 'text-indigo-600 bg-indigo-50'
                             : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    Mes Inscriptions
                </a>
                <a href="{{ url('/events/create') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->is('events/create')
                             ? 'text-indigo-600 bg-indigo-50'
                             : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    Créer un événement
                </a>
            </nav>

            {{-- Badge Mode Démo --}}
            <span class="badge-demo hidden lg:inline-flex items-center
                         text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0">
                Mode Démo : Inscriptions sans frais
            </span>

            {{-- Burger mobile --}}
            <button id="nav-toggle" aria-label="Menu" aria-expanded="false"
                    class="md:hidden ml-auto p-2 rounded-lg text-gray-500
                           hover:bg-gray-100 transition-colors">
                <i id="burger-icon" class="fa-solid fa-bars text-base"></i>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div id="mobile-menu"
         class="md:hidden hidden border-t border-gray-100 bg-white">
        {{-- Recherche mobile --}}
        <div class="px-4 pt-3 pb-2">
            <form method="GET" action="{{ url('/events') }}">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2
                               text-gray-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un événement…"
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-100 border border-transparent
                                  rounded-xl focus:outline-none focus:bg-white focus:border-indigo-300" />
                </div>
            </form>
        </div>
        {{-- Liens --}}
        <div class="px-4 pb-3 space-y-1">
            <a href="{{ url('/events') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->is('events*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa-solid fa-compass w-4 text-center text-gray-400"></i>Découvrir
            </a>
            <a href="{{ url('/my-registrations') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->is('my-registrations*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa-solid fa-ticket w-4 text-center text-gray-400"></i>Mes Inscriptions
            </a>
            <a href="{{ url('/events/create') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->is('events/create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa-solid fa-plus w-4 text-center text-gray-400"></i>Créer un événement
            </a>
        </div>
        {{-- Badge démo mobile --}}
        <div class="px-4 pb-4">
            <span class="badge-demo inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-full">
                Mode Démo : Inscriptions sans frais
            </span>
        </div>
    </div>
</header>

{{-- ================================================================
     FLASH MESSAGES
     ================================================================ --}}
@if(session('success') || session('error') || session('warning') || session('info'))
<div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4 space-y-2">

    @if(session('success'))
    <div class="flash-in flex items-start gap-3 bg-green-50 border border-green-200
                text-green-800 px-4 py-3 rounded-xl" role="alert">
        <i class="fa-solid fa-circle-check text-green-500 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
        <button onclick="this.closest('[role=alert]').remove()"
                class="text-green-400 hover:text-green-700 ml-2 flex-shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="flash-in flex items-start gap-3 bg-red-50 border border-red-200
                text-red-800 px-4 py-3 rounded-xl" role="alert">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
        <button onclick="this.closest('[role=alert]').remove()"
                class="text-red-400 hover:text-red-700 ml-2 flex-shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="flash-in flex items-start gap-3 bg-amber-50 border border-amber-200
                text-amber-800 px-4 py-3 rounded-xl" role="alert">
        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('warning') }}</p>
        <button onclick="this.closest('[role=alert]').remove()"
                class="text-amber-400 hover:text-amber-700 ml-2 flex-shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="flash-in flex items-start gap-3 bg-indigo-50 border border-indigo-200
                text-indigo-800 px-4 py-3 rounded-xl" role="alert">
        <i class="fa-solid fa-circle-info text-indigo-500 mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-medium flex-1">{{ session('info') }}</p>
        <button onclick="this.closest('[role=alert]').remove()"
                class="text-indigo-400 hover:text-indigo-700 ml-2 flex-shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

</div>
@endif

{{-- ================================================================
     CONTENU PRINCIPAL
     ================================================================ --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ================================================================
     FOOTER — fidèle au Stitch
     ================================================================ --}}
<footer class="bg-white border-t border-gray-100 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

            {{-- Logo + copyright --}}
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-white text-xs"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700">
                    Event<span class="text-indigo-600">Hub</span>
                </span>
                <span class="text-xs text-gray-400 hidden sm:block">
                    © {{ date('Y') }} EventHub Ecosystem. Tous droits réservés.
                </span>
            </div>

            {{-- Mention stack --}}
            <p class="text-xs text-gray-400 hidden md:block">
                • Propulsé par Laravel Blade & Tailwind CSS
            </p>

            {{-- Liens légaux --}}
            <div class="flex items-center gap-5 text-xs text-gray-500">
                <a href="#" class="hover:text-indigo-600 transition-colors">Conditions Générales</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Confidentialité</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Support</a>
            </div>

        </div>
        {{-- Copyright mobile --}}
        <p class="text-xs text-gray-400 text-center mt-3 sm:hidden">
            © {{ date('Y') }} EventHub Ecosystem. Tous droits réservés.
        </p>
    </div>
</footer>

{{-- ================================================================
     SCRIPTS
     ================================================================ --}}
<script>
    // Toggle menu mobile
    const toggle = document.getElementById('nav-toggle');
    const menu   = document.getElementById('mobile-menu');
    const icon   = document.getElementById('burger-icon');

    toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('hidden');
        icon.className = open
            ? 'fa-solid fa-bars text-base'
            : 'fa-solid fa-xmark text-base';
        toggle.setAttribute('aria-expanded', String(!open));
    });

    // Auto-dismiss flash après 5s
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