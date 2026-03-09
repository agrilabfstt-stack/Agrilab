@extends('layouts.student')

@section('title', 'Mes projets')
@section('page-title', 'Mes projets')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-gray-500">{{ $projects->total() }} projet(s)</p>
    <a href="{{ route('student.projects.create') }}"
       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau projet
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($projects as $project)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition-shadow">
        <div class="p-5 flex-1">
            <div class="flex items-start justify-between gap-2 mb-3">
                <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 leading-snug">{{ $project->title }}</h3>
                <span class="text-xs px-2 py-1 rounded-full flex-shrink-0
                    @if($project->status === 'active') bg-green-100 text-green-700
                    @elseif($project->status === 'blocked') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $project->statusLabel() }}
                </span>
            </div>
            @if($project->category)
            <span class="inline-block text-xs px-2 py-0.5 rounded-full text-white mb-2" style="background-color: {{ $project->category->color }}">
                {{ $project->category->name }}
            </span>
            @endif
            <p class="text-xs text-gray-500 line-clamp-3 mb-3">
                {{ Str::limit(strip_tags($project->description), 120) }}
            </p>
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span>{{ $project->data->count() }} section(s)</span>
                <span>{{ $project->attachments->count() }} fichier(s)</span>
                <span>{{ $project->comments->count() }} commentaire(s)</span>
            </div>
        </div>
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $project->updated_at->diffForHumans() }}</span>
            <div class="flex gap-2">
                <a href="{{ route('student.projects.show', $project) }}"
                   class="text-xs px-3 py-1.5 border border-gray-200 hover:bg-gray-50 text-gray-600 rounded-lg transition-colors">
                    Voir
                </a>
                @if(!$project->isBlocked())
                <a href="{{ route('student.projects.edit', $project) }}"
                   class="text-xs px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Éditer
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-sm mb-4">Vous n'avez aucun projet.</p>
        <a href="{{ route('student.projects.create') }}"
           class="inline-block px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
            Créer mon premier projet
        </a>
    </div>
    @endforelse
</div>

@if($projects->hasPages())
<div class="mt-6">{{ $projects->links() }}</div>
@endif
@endsection
