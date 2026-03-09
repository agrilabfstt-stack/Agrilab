@extends('layouts.admin')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Professeurs</span>
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['professors'] }}</div>
        <a href="{{ route('admin.users.index', ['role' => 'professor']) }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Voir tous →</a>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Étudiants</span>
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['students'] }}</div>
        <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="text-xs text-green-600 hover:underline mt-1 inline-block">Voir tous →</a>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Projets</span>
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['projects'] }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Catégories</span>
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['categories'] }}</div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-orange-600 hover:underline mt-1 inline-block">Gérer →</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Derniers utilisateurs</h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:underline">Voir tout →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $user)
            <div class="flex items-center gap-4 px-5 py-3.5">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white {{ $user->role === 'professor' ? 'bg-blue-500' : 'bg-green-500' }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $user->role === 'professor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                    {{ $user->role === 'professor' ? 'Professeur' : 'Étudiant' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400 text-sm">Aucun utilisateur pour le moment.</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Projects --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Derniers projets</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentProjects as $project)
            <div class="flex items-center gap-4 px-5 py-3.5">
                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $project->title }}</p>
                    <p class="text-xs text-gray-400">{{ $project->user->name ?? '—' }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                    @if($project->status === 'active') bg-green-100 text-green-700
                    @elseif($project->status === 'blocked') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $project->statusLabel() }}
                </span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400 text-sm">Aucun projet pour le moment.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{ route('admin.users.create') }}"
       class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl p-5 transition-colors">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <div class="font-semibold">Créer un utilisateur</div>
            <div class="text-sm text-green-100">Ajouter un professeur ou étudiant</div>
        </div>
    </a>
    <a href="{{ route('admin.categories.index') }}"
       class="flex items-center gap-4 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl p-5 transition-colors">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div>
            <div class="font-semibold">Gérer les catégories</div>
            <div class="text-sm text-orange-100">Créer, modifier ou supprimer</div>
        </div>
    </a>
</div>
@endsection
