@extends('layouts.professor')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Étudiants</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-gray-900">{{ $stats['projects'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Projets total</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Actifs</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-blue-600">{{ $stats['completed'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Terminés</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Students list --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Mes étudiants</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($students as $student)
                <div class="flex items-center gap-3 px-5 py-3.5">
                    <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center text-sm font-bold text-white">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $student->name }}</p>
                        <p class="text-xs text-gray-400">{{ $student->projects_count }} projet(s)</p>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">
                    Aucun étudiant assigné.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent projects --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Projets récents</h3>
                <a href="{{ route('professor.projects.index') }}" class="text-sm text-green-600 hover:underline">Voir tout →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentProjects as $project)
                <div class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('professor.projects.show', $project) }}"
                           class="font-medium text-gray-900 hover:text-green-600 transition-colors text-sm">
                            {{ $project->title }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $project->user->name ?? '—' }}
                            @if($project->category) · <span style="color: {{ $project->category->color }}">{{ $project->category->name }}</span> @endif
                        </p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full flex-shrink-0
                        @if($project->status === 'active') bg-green-100 text-green-700
                        @elseif($project->status === 'blocked') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $project->statusLabel() }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-12 text-center text-gray-400 text-sm">
                    Aucun projet pour le moment.
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('professor.projects.create') }}"
               class="flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl p-5 transition-colors">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold">Créer un projet pour un étudiant</div>
                    <div class="text-sm text-green-100">Assigner et démarrer un nouveau projet</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
