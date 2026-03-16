@extends('layouts.professor')

@section('title', 'Projets')
@section('page-title', 'Projets des étudiants')

@section('content')
<div class="mb-5 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
    <form method="GET" action="{{ route('professor.projects.index') }}" class="flex flex-wrap gap-2">
        <select name="student_id" onchange="this.form.submit()"
                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Tous les étudiants</option>
            @foreach($students as $student)
            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                {{ $student->name }}
            </option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()"
                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Tous les statuts</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Bloqué</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
        </select>
        @if(request()->anyFilled(['student_id','status']))
        <a href="{{ route('professor.projects.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm text-gray-600 transition-colors">Réinitialiser</a>
        @endif
    </form>
    <a href="{{ route('professor.projects.create') }}"
       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau projet
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($projects as $project)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-start justify-between gap-3 mb-3">
                <h3 class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2">{{ $project->title }}</h3>
                <span class="text-xs px-2 py-1 rounded-full flex-shrink-0
                    @if($project->status === 'active') bg-green-100 text-green-700
                    @elseif($project->status === 'blocked') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $project->statusLabel() }}
                </span>
            </div>
            <p class="text-xs text-gray-400 mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $project->user->name ?? '—' }}
                @if($project->category)
                <span class="w-2 h-2 rounded-full inline-block" style="background-color: {{ $project->category->color }}"></span>
                {{ $project->category->name }}
                @endif
            </p>
            <p class="text-xs text-gray-500 line-clamp-3">{{ Str::limit(strip_tags($project->description), 100) }}</p>
        </div>
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $project->updated_at->diffForHumans() }}</span>
            <div class="flex gap-2">
                <button onclick="toggleShowcase({{ $project->id }}, this)"
                        class="text-xs px-3 py-1.5 rounded-lg border transition-colors
                        {{ $project->is_showcased ? 'border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                    {{ $project->is_showcased ? '⭐ Showcase' : '☆ Showcase' }}
                </button>
                <form method="POST" action="{{ route('professor.projects.toggle-status', $project) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="text-xs px-3 py-1.5 rounded-lg border transition-colors
                            @if($project->status === 'blocked') border-green-300 text-green-700 hover:bg-green-50
                            @else border-red-300 text-red-600 hover:bg-red-50 @endif">
                        {{ $project->status === 'blocked' ? 'Débloquer' : 'Bloquer' }}
                    </button>
                </form>
                <a href="{{ route('professor.projects.show', $project) }}"
                   class="text-xs px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Voir
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-sm">Aucun projet trouvé.</p>
    </div>
    @endforelse
</div>

@if($projects->hasPages())
<div class="mt-6">{{ $projects->withQueryString()->links() }}</div>
@endif

@push('scripts')
<script>
async function toggleShowcase(projectId, btn) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    try {
        const fd = new FormData();
        fd.append('_method', 'PATCH');
        const resp = await fetch('/projects/' + projectId + '/showcase', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: fd
        });
        if (!resp.ok) throw new Error('Erreur');
        const data = await resp.json();
        if (data.is_showcased) {
            btn.classList.add('border-amber-300', 'text-amber-700', 'bg-amber-50', 'hover:bg-amber-100');
            btn.classList.remove('border-gray-300', 'text-gray-600', 'hover:bg-gray-50');
            btn.textContent = '⭐ Showcase';
        } else {
            btn.classList.remove('border-amber-300', 'text-amber-700', 'bg-amber-50', 'hover:bg-amber-100');
            btn.classList.add('border-gray-300', 'text-gray-600', 'hover:bg-gray-50');
            btn.textContent = '☆ Showcase';
        }
    } catch (e) {
        alert('Erreur lors de la mise à jour du showcase.');
    }
}
</script>
@endpush
@endsection
