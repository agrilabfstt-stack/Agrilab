<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Professeur') — Agrilab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden">
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 flex-shrink-0 shadow-sm">
        <div class="p-4 border-b border-gray-200 flex items-center gap-3">
            <img x-show="sidebarOpen" src="{{ asset('logo.png') }}" alt="Agrilab" class="h-9">
            <img x-show="!sidebarOpen" src="{{ asset('logo.png') }}" alt="Agrilab" class="h-7 w-7 object-contain">
        </div>
        <nav class="flex-1 py-4 overflow-y-auto">
            <a href="{{ route('professor.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('professor.dashboard') ? 'bg-green-50 text-green-700 font-medium border-r-2 border-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen">Tableau de bord</span>
            </a>
            <a href="{{ route('professor.projects.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('professor.projects.*') ? 'bg-green-50 text-green-700 font-medium border-r-2 border-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen">Projets des étudiants</span>
            </a>
            <a href="{{ route('professor.projects.create') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span x-show="sidebarOpen">Créer un projet</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-200">
            <div x-show="sidebarOpen" class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400">Professeur</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 text-sm text-gray-500 hover:text-red-600 transition-colors py-1">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="sidebarOpen">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Tableau de bord')</h1>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
