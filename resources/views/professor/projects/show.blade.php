@extends('layouts.professor')

@section('title', $project->title)
@section('page-title', 'Détail du projet')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ lightboxUrl: '' }">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4 justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
                        <span class="text-xs font-medium px-3 py-1 rounded-full
                            @if($project->status === 'active') bg-green-100 text-green-700
                            @elseif($project->status === 'blocked') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $project->statusLabel() }}
                        </span>
                        @if($project->category)
                        <span class="text-xs px-2.5 py-1 rounded-full text-white font-medium" style="background-color: {{ $project->category->color }}">
                            {{ $project->category->name }}
                        </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">
                        Étudiant : <span class="font-medium text-gray-900">{{ $project->user->name ?? '—' }}</span>
                        · Créé {{ $project->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('professor.projects.edit', $project) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('professor.projects.toggle-status', $project) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors
                                @if($project->status === 'blocked') bg-green-50 text-green-700 hover:bg-green-100
                                @else bg-red-50 text-red-700 hover:bg-red-100 @endif">
                            @if($project->status === 'blocked')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            Débloquer
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Bloquer
                            @endif
                        </button>
                    </form>
                    @if($project->status !== 'completed')
                    <form method="POST" action="{{ route('professor.projects.toggle-status', $project) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Terminer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 pb-6 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-sm font-semibold text-gray-700">Description</h3>
            </div>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! \Illuminate\Support\Str::markdown($project->description ?? '') !!}
            </div>
        </div>
    </div>

    {{-- Data Sections --}}
    @if($project->data->count())
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Sections de données</h3>
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $project->data->count() }}</span>
        </div>

        @foreach($project->data as $section)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm
                    @if($section->type === 'value') bg-emerald-100 text-emerald-600
                    @elseif($section->type === 'image') bg-blue-100 text-blue-600
                    @elseif($section->type === 'table') bg-amber-100 text-amber-600
                    @else bg-purple-100 text-purple-600 @endif">
                    @if($section->type === 'value')<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    @elseif($section->type === 'image')<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @elseif($section->type === 'table')<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    @else<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    @endif
                </div>
                <h4 class="font-semibold text-gray-800">{{ $section->name }}</h4>
            </div>

            <div class="p-6">
                @if($section->type === 'value')
                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $section->content['value'] ?? '' }}</div>

                @elseif($section->type === 'image')
                    @if($section->files->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($section->files as $file)
                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer"
                             @click="lightboxUrl = '{{ Storage::url($file->path) }}'">
                            <img src="{{ Storage::url($file->path) }}" alt="{{ $file->original_name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="p-2 bg-white/90 rounded-full shadow-sm">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-400 text-sm text-center py-4">Aucune image.</p>
                    @endif

                @elseif($section->type === 'table')
                    @php $tableData = $section->content; @endphp
                    @if(!empty($tableData['columns']))
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    @foreach($tableData['columns'] as $col)
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 border-b border-r border-gray-200 last:border-r-0">{{ $col }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData['rows'] ?? [] as $row)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    @foreach($row as $cell)
                                    <td class="px-4 py-2.5 text-gray-700 border-b border-r border-gray-200 last:border-r-0">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                @elseif($section->type === 'chart')
                    @php $chartData = $section->content; @endphp
                    <div class="max-w-lg mx-auto">
                        <canvas id="chart-{{ $section->id }}" height="280"></canvas>
                    </div>
                    @push('scripts')
                    <script>
                    (function() {
                        const ctx = document.getElementById('chart-{{ $section->id }}').getContext('2d');
                        const data = @json($chartData);
                        if (!data || !data.chart_type) return;
                        const colors = ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'];
                        const isCircular = ['pie','doughnut','polarArea'].includes(data.chart_type);
                        const datasets = (data.datasets || []).map((ds, i) => ({
                            ...ds,
                            backgroundColor: isCircular ? (data.labels||[]).map((_,j) => colors[j%colors.length]) : colors[i%colors.length]+'40',
                            borderColor: isCircular ? colors : colors[i%colors.length],
                            borderWidth: 2, tension: 0.3,
                        }));
                        new Chart(ctx, {
                            type: data.chart_type,
                            data: { labels: data.labels || [], datasets },
                            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: isCircular ? {} : { y: { beginAtZero: true } } }
                        });
                    })();
                    </script>
                    @endpush
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Attachments --}}
    @if($project->attachments->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            <h3 class="font-semibold text-gray-800">Fichiers joints</h3>
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $project->attachments->count() }}</span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($project->attachments as $attachment)
                <div class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                    @if($attachment->isImage())
                    <div class="aspect-square cursor-pointer" @click="lightboxUrl = '{{ Storage::url($attachment->path) }}'">
                        <img src="{{ Storage::url($attachment->path) }}" alt="{{ $attachment->original_name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="p-2 bg-white/90 rounded-full shadow-sm">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($attachment->isVideo())
                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="aspect-square flex items-center justify-center bg-gray-900">
                        <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </a>
                    @else
                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="aspect-square flex flex-col items-center justify-center gap-2 p-3 hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs text-gray-500 text-center truncate max-w-full">{{ $attachment->original_name }}</span>
                    </a>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-xs text-white truncate block">{{ Str::limit($attachment->original_name, 22) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Comments --}}
    @include('partials.comments', ['project' => $project])

    {{-- Lightbox --}}
    <div x-show="lightboxUrl" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black/90 flex items-center justify-center z-[200] p-4 cursor-pointer" @click="lightboxUrl = ''" @keydown.escape.window="lightboxUrl = ''">
        <button class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="lightboxUrl" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
    </div>
</div>
@endsection
