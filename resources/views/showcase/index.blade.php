@extends('layouts.app')

@section('title', 'Showcase — Agrilab')

@section('content')
<div class="bg-gradient-to-br from-green-700 via-green-800 to-emerald-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4">🏆 Showcase des meilleurs projets</h1>
        <p class="text-green-200 text-lg max-w-2xl mx-auto">Découvrez les projets expérimentaux les plus remarquables sélectionnés par nos professeurs.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="showcaseFilters()">
    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" @input.debounce.400ms="applyFilters()"
                       placeholder="Rechercher un projet..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <select x-model="category" @change="applyFilters()"
                    class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select x-model="year" @change="applyFilters()"
                    class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Toutes les années</option>
                @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <select x-model="professor" @change="applyFilters()"
                    class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Tous les professeurs</option>
                @foreach($professors as $prof)
                <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                @endforeach
            </select>
            <template x-if="search || category || year || professor">
                <button @click="resetFilters()" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm text-gray-600 transition-colors whitespace-nowrap">
                    Réinitialiser
                </button>
            </template>
        </div>
    </div>

    {{-- Results count --}}
    <div class="text-sm text-gray-500 mb-6">
        {{ $projects->total() }} projet(s) dans le showcase
    </div>

    {{-- Project Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
            {{-- Preview Image --}}
            @php
                $previewImage = $project->attachments->first(fn($a) => $a->isImage());
            @endphp
            <div class="aspect-video bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
                @if($previewImage)
                <img src="{{ Storage::url($previewImage->path) }}" alt="{{ $project->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                @endif
                @if($project->category)
                <span class="absolute top-3 left-3 text-xs px-2.5 py-1 rounded-full text-white font-medium shadow-sm" style="background-color: {{ $project->category->color }}">
                    {{ $project->category->name }}
                </span>
                @endif
            </div>

            <div class="p-5 flex-1 flex flex-col">
                <h3 class="font-semibold text-gray-900 text-base mb-2 line-clamp-2">{{ $project->title }}</h3>
                <p class="text-sm text-gray-500 mb-3 line-clamp-3">{{ Str::limit(strip_tags($project->description), 120) }}</p>

                <div class="mt-auto space-y-2 text-xs text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Étudiant : <span class="text-gray-600 font-medium">{{ $project->user->name ?? '—' }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Professeur : <span class="text-gray-600 font-medium">{{ $project->creator->name ?? ($project->user->professor->name ?? '—') }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $project->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3.5 border-t border-gray-100">
                <a href="{{ route('showcase.show', $project) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Voir le projet
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            <p class="text-gray-400 text-lg">Aucun projet dans le showcase pour le moment.</p>
            <p class="text-gray-300 text-sm mt-1">Les meilleurs projets seront bientôt publiés ici.</p>
        </div>
        @endforelse
    </div>

    @if($projects->hasPages())
    <div class="mt-8">{{ $projects->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function showcaseFilters() {
    const params = new URLSearchParams(window.location.search);
    return {
        search: params.get('search') || '',
        category: params.get('category') || '',
        year: params.get('year') || '',
        professor: params.get('professor') || '',
        applyFilters() {
            const p = new URLSearchParams();
            if (this.search) p.set('search', this.search);
            if (this.category) p.set('category', this.category);
            if (this.year) p.set('year', this.year);
            if (this.professor) p.set('professor', this.professor);
            window.location.href = '{{ route("showcase.index") }}' + (p.toString() ? '?' + p.toString() : '');
        },
        resetFilters() {
            this.search = '';
            this.category = '';
            this.year = '';
            this.professor = '';
            window.location.href = '{{ route("showcase.index") }}';
        }
    }
}
</script>
@endpush
