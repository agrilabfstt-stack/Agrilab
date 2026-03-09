@extends('layouts.app')

@section('title', 'Agrilab — Plateforme de Gestion de Projets Agricoles')

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 text-white overflow-hidden min-h-[600px] flex items-center">
    <div class="absolute inset-0 opacity-[0.07]">
        <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse">
                <path d="M 30 0 L 0 0 0 30" fill="none" stroke="white" stroke-width="0.5"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>
    <div class="absolute top-20 right-10 w-72 h-72 bg-green-400/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-emerald-300/10 rounded-full blur-3xl"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm mb-8 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                    <span class="text-green-100">Plateforme pédagogique agricole</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                    Gérez vos projets<br>agricoles avec
                    <span class="bg-gradient-to-r from-yellow-300 to-amber-400 bg-clip-text text-transparent">Agrilab</span>
                </h1>
                <p class="text-lg text-green-100/90 leading-relaxed mb-10 max-w-lg">
                    La plateforme collaborative qui connecte étudiants et professeurs autour de projets agricoles innovants. Données, graphiques, commentaires — tout en un.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2.5 bg-white text-green-800 font-bold px-8 py-4 rounded-2xl hover:bg-green-50 transition-all shadow-xl shadow-black/20 text-base">
                        Commencer maintenant
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#features"
                       class="inline-flex items-center justify-center gap-2 border-2 border-white/20 text-white px-8 py-4 rounded-2xl hover:bg-white/10 transition-all text-base font-medium backdrop-blur-sm">
                        Explorer les fonctionnalités
                    </a>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/15 hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="font-semibold mb-1.5">Graphiques interactifs</h3>
                            <p class="text-sm text-green-200/80">6 types de graphiques : barres, lignes, secteurs, anneau, polaire, radar</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/15 hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="font-semibold mb-1.5">Galerie d'images</h3>
                            <p class="text-sm text-green-200/80">Visualisez en plein écran avec un système de lightbox intégré</p>
                        </div>
                    </div>
                    <div class="space-y-4 mt-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/15 hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="font-semibold mb-1.5">Commentaires</h3>
                            <p class="text-sm text-green-200/80">Échangez en temps réel sur chaque projet</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/15 hover:bg-white/15 transition-colors">
                            <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h3 class="font-semibold mb-1.5">Accès sécurisé</h3>
                            <p class="text-sm text-green-200/80">3 rôles : Admin, Professeur, Étudiant</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 80L1440 80L1440 20C1080 80 360 0 0 60L0 80Z" fill="#f9fafb"/></svg>
    </div>
</section>

{{-- Stats --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 relative z-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-green-700 mb-1">{{ $stats['students'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Étudiants inscrits</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-blue-700 mb-1">{{ $stats['professors'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Professeurs</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-purple-700 mb-1">{{ $stats['projects'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Projets actifs</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-orange-600 mb-1">{{ $stats['categories'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Catégories</div>
        </div>
    </div>
</section>

{{-- Features Grid --}}
<section id="features" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">Fonctionnalités</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Tout ce dont vous avez besoin</h2>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Agrilab centralise la gestion de vos projets pédagogiques avec un ensemble complet d'outils collaboratifs.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Feature 1 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-green-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-5 group-hover:bg-green-200 transition-colors">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Gestion de projets</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Créez, modifiez et suivez vos projets agricoles. Organisez-les par catégories avec un système de statuts (actif, bloqué, terminé).</p>
        </div>
        {{-- Feature 2 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-5 group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Éditeur Markdown</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Rédigez vos descriptions de projet avec un éditeur Markdown riche (gras, italique, listes, liens, images) et un aperçu en direct.</p>
        </div>
        {{-- Feature 3 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-5 group-hover:bg-purple-200 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Graphiques interactifs</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Créez 6 types de graphiques (barres, lignes, secteurs, anneau, polaire, radar) avec prévisualisation en temps réel et séries de données multiples.</p>
        </div>
        {{-- Feature 4 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-amber-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-5 group-hover:bg-amber-200 transition-colors">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tableaux de données</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Éditeur de tableaux dynamique : ajoutez ou supprimez colonnes et lignes à la volée. Idéal pour vos résultats d'analyse et vos mesures.</p>
        </div>
        {{-- Feature 5 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-cyan-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-cyan-100 flex items-center justify-center mb-5 group-hover:bg-cyan-200 transition-colors">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Galerie d'images & lightbox</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Importez vos photos de terrain et de laboratoire. Cliquez pour les agrandir en plein écran grâce au système de lightbox intégré.</p>
        </div>
        {{-- Feature 6 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-rose-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center mb-5 group-hover:bg-rose-200 transition-colors">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Fichiers joints</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Ajoutez des images, vidéos, PDF et documents Office à vos projets. Glissez-déposez ou sélectionnez depuis votre appareil.</p>
        </div>
        {{-- Feature 7 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-5 group-hover:bg-indigo-200 transition-colors">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Commentaires en temps réel</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Commentez les projets sans rechargement de page. Les échanges se font en Ajax pour une expérience fluide et instantanée.</p>
        </div>
        {{-- Feature 8 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-teal-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center mb-5 group-hover:bg-teal-200 transition-colors">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Gestion des rôles</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Trois espaces dédiés : Administrateur (gestion complète), Professeur (suivi des étudiants), Étudiant (gestion de projets).</p>
        </div>
        {{-- Feature 9 --}}
        <div class="group bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-lg hover:border-orange-200 transition-all">
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-5 group-hover:bg-orange-200 transition-colors">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Catégories colorées</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Organisez vos projets par thème : Agronomie, Zootechnie, Agroécologie, Hydraulique… Chaque catégorie a sa propre couleur.</p>
        </div>
    </div>
</section>

{{-- Role Sections --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">Pour chaque rôle</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Un espace adapté à chacun</h2>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">Chaque utilisateur dispose d'une interface et de fonctionnalités adaptées à son rôle.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="relative bg-gradient-to-b from-green-50 to-white rounded-2xl p-8 border border-green-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-100/50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-green-600 flex items-center justify-center mb-6 shadow-lg shadow-green-200">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Espace Étudiant</h3>
                    <ul class="text-sm text-gray-600 space-y-3">
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Créer et éditer ses projets</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Ajouter textes, images, tableaux et graphiques</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Joindre des fichiers multimédia</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Échanger via les commentaires</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Tableau de bord avec statistiques</li>
                    </ul>
                </div>
            </div>
            <div class="relative bg-gradient-to-b from-blue-50 to-white rounded-2xl p-8 border border-blue-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Espace Professeur</h3>
                    <ul class="text-sm text-gray-600 space-y-3">
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Suivre les projets de ses étudiants</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Commenter et accompagner</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Bloquer / Débloquer / Terminer un projet</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Créer des projets pour ses étudiants</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Filtrer par étudiant et par statut</li>
                    </ul>
                </div>
            </div>
            <div class="relative bg-gradient-to-b from-purple-50 to-white rounded-2xl p-8 border border-purple-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100/50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-purple-200">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Espace Administrateur</h3>
                    <ul class="text-sm text-gray-600 space-y-3">
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Créer professeurs et étudiants</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Assigner étudiants aux professeurs</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Gérer les catégories (Ajax, sans rechargement)</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Tableau de bord analytique global</li>
                        <li class="flex items-start gap-2.5"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Vue d'ensemble de tous les projets</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-600"></div>
    <div class="absolute inset-0 opacity-10">
        <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <pattern id="cta-grid" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="1" cy="1" r="1" fill="white"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#cta-grid)"/>
        </svg>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">Prêt à commencer ?</h2>
        <p class="text-green-100 text-lg mb-10 max-w-xl mx-auto">Connectez-vous dès maintenant pour accéder à votre espace Agrilab et gérer vos projets agricoles.</p>
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2.5 bg-white text-green-800 font-bold px-10 py-4 rounded-2xl hover:bg-green-50 transition-all shadow-xl text-lg">
            Se connecter
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

@endsection
