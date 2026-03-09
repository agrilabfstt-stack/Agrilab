@extends('layouts.app')

@section('title', 'Connexion — Agrilab')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
                <span class="text-3xl">🌱</span>
                <span class="text-2xl font-bold text-green-700">Agrilab</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Connexion à votre espace</h1>
            <p class="text-gray-500 mt-2 text-sm">Entrez vos identifiants pour accéder à la plateforme</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Adresse e-mail
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           autofocus
                           class="w-full px-4 py-2.5 border @error('email') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                           placeholder="vous@exemple.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Mot de passe
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Se connecter
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Vous n'avez pas de compte ? Contactez l'administrateur.
        </p>
    </div>
</div>
@endsection
