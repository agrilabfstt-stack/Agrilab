@extends('layouts.app')

@section('title', 'Idées de recherche — Agrilab')

@section('content')
<div class="bg-gradient-to-br from-blue-700 via-indigo-800 to-purple-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4">💡 Idées de recherche</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">Proposez et découvrez des idées de projets expérimentaux. Collaborez avant de lancer un vrai projet.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ideasFilter()">
    {{-- Filters & Actions --}}
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-8">
        <div class="flex flex-wrap gap-3 items-center">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" @input.debounce.400ms="applyFilters()"
                       placeholder="Rechercher..."
                       class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select x-model="status" @change="applyFilters()"
                    class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="open">Ouvert</option>
                <option value="in_progress">En cours</option>
                <option value="completed">Terminé</option>
            </select>
            <template x-if="search || status">
                <button @click="resetFilters()" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm text-gray-600 transition-colors">
                    Réinitialiser
                </button>
            </template>
        </div>
        @auth
        <a href="{{ route('ideas.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Proposer une idée
        </a>
        @endauth
    </div>

    {{-- Results count --}}
    <div class="text-sm text-gray-500 mb-6">
        {{ $ideas->total() }} idée(s) de recherche
    </div>

    {{-- Ideas Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($ideas as $idea)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <h3 class="font-semibold text-gray-900 text-base line-clamp-2">{{ $idea->title }}</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full flex-shrink-0 font-medium
                        @if($idea->status === 'open') bg-green-100 text-green-700
                        @elseif($idea->status === 'in_progress') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $idea->statusLabel() }}
                    </span>
                </div>

                <p class="text-sm text-gray-500 mb-3 line-clamp-3">{{ Str::limit(strip_tags($idea->description), 120) }}</p>

                @if($idea->tags && count($idea->tags))
                <div class="flex flex-wrap gap-1.5 mb-3">
                    @foreach($idea->tags as $tag)
                    <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                <div class="flex items-center gap-3 text-xs text-gray-400 mt-auto">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $idea->author->name ?? '—' }}
                    </span>
                    <span>·</span>
                    <span>{{ $idea->created_at->diffForHumans() }}</span>
                    @if($idea->participants->count())
                    <span>·</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $idea->participants->count() }} participant(s)
                    </span>
                    @endif
                </div>
            </div>

            <div class="px-5 py-3.5 border-t border-gray-100">
                <a href="{{ route('ideas.show', $idea) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Voir l'idée
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <p class="text-gray-400 text-lg">Aucune idée de recherche pour le moment.</p>
            @auth
            <a href="{{ route('ideas.create') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                Proposer la première idée
            </a>
            @endauth
        </div>
        @endforelse
    </div>

    @if($ideas->hasPages())
    <div class="mt-8">{{ $ideas->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function ideasFilter() {
    const params = new URLSearchParams(window.location.search);
    return {
        search: params.get('search') || '',
        status: params.get('status') || '',
        applyFilters() {
            const p = new URLSearchParams();
            if (this.search) p.set('search', this.search);
            if (this.status) p.set('status', this.status);
            window.location.href = '{{ route("ideas.index") }}' + (p.toString() ? '?' + p.toString() : '');
        },
        resetFilters() {
            this.search = '';
            this.status = '';
            window.location.href = '{{ route("ideas.index") }}';
        }
    }
}
</script>
@endpush
