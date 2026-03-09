<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agrilab')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Agrilab" class="h-9">
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Accueil</a>
                        <a href="{{ route('home') }}#features" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Fonctionnalités</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                           class="text-sm font-medium text-green-700 hover:text-green-800 transition-colors">
                            Mon espace
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-sm font-medium bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-semibold bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition-colors shadow-sm">
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-gray-900 text-white py-14 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Agrilab" class="h-8 brightness-0 invert">
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                        Plateforme collaborative de gestion de projets agricoles entre étudiants et enseignants. Innovez, partagez et apprenez ensemble.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Accueil</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Connexion</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400 text-sm">📧 contact@agrilab.dz</li>
                        <li class="text-gray-400 text-sm">📞 +213 555 123 456</li>
                        <li class="text-gray-400 text-sm">📍 Alger, Algérie</li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Agrilab — Tous droits réservés.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
