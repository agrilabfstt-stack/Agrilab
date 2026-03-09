<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — Agrilab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="bg-gray-900 text-white flex flex-col transition-all duration-300 flex-shrink-0">
        <div class="p-4 border-b border-gray-700 flex items-center gap-3">
            <img x-show="sidebarOpen" src="{{ asset('logo.png') }}" alt="Agrilab" class="h-9 brightness-0 invert">
            <img x-show="!sidebarOpen" src="{{ asset('logo.png') }}" alt="Agrilab" class="h-7 w-7 object-contain brightness-0 invert">
        </div>
        <nav class="flex-1 py-4 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen">Tableau de bord</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.users.*') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="sidebarOpen">Utilisateurs</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span x-show="sidebarOpen">Catégories</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <div x-show="sidebarOpen" class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400">Administrateur</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 text-sm text-gray-400 hover:text-white transition-colors py-1">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="sidebarOpen">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Tableau de bord')</h1>
            </div>
            <div class="text-sm text-gray-400">{{ now()->isoFormat('dddd D MMMM YYYY') }}</div>
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
