@extends('layouts.student')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Total projets</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
        <div class="text-sm text-gray-500 mt-1">En cours</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-blue-600">{{ $stats['completed'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Terminés</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-red-500">{{ $stats['blocked'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Bloqués</div>
    </div>
</div>

{{-- My projects --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Mes projets récents</h3>
        <a href="{{ route('student.projects.index') }}" class="text-sm text-green-600 hover:underline">Voir tout →</a>
    </div>
    <div class="divide-y divide-gray-50">
        @forelse($projects as $project)
        <div class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <a href="{{ route('student.projects.show', $project) }}"
                       class="font-medium text-gray-900 hover:text-green-600 transition-colors text-sm">
                        {{ $project->title }}
                    </a>
                    @if($project->status === 'blocked')
                    <span class="text-xs px-2 py-0.5 bg-red-100 text-red-600 rounded-full">Bloqué</span>
                    @elseif($project->status === 'completed')
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-600 rounded-full">Terminé</span>
                    @endif
                </div>
                <p class="text-xs text-gray-400">
                    Modifié {{ $project->updated_at->diffForHumans() }}
                    @if($project->category) · {{ $project->category->name }} @endif
                </p>
            </div>
            @if(!$project->isBlocked())
            <a href="{{ route('student.projects.edit', $project) }}"
               class="text-xs px-3 py-1.5 border border-gray-200 hover:bg-gray-100 text-gray-600 rounded-lg transition-colors flex-shrink-0">
                Éditer
            </a>
            @endif
        </div>
        @empty
        <div class="px-5 py-12 text-center text-gray-400 text-sm">
            Vous n'avez aucun projet pour le moment.
        </div>
        @endforelse
    </div>
</div>

{{-- Professor info --}}
@if(auth()->user()->professor)
<div class="bg-green-50 border border-green-200 rounded-2xl p-5">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
            {{ strtoupper(substr(auth()->user()->professor->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->professor->name }}</p>
            <p class="text-xs text-gray-500">Votre professeur responsable</p>
        </div>
    </div>
</div>
@else
<div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
    <p class="text-sm text-orange-700">Vous n'êtes pas encore assigné à un professeur.</p>
</div>
@endif

<div class="mt-5">
    <a href="{{ route('student.projects.create') }}"
       class="flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl p-5 transition-colors">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <div class="font-semibold">Créer un nouveau projet</div>
            <div class="text-sm text-green-100">Commencer un nouveau projet agricole</div>
        </div>
    </a>
</div>
@endsection
