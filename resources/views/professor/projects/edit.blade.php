@extends('layouts.professor')

@section('title', 'Modifier « ' . $project->title . ' »')
@section('page-title', 'Modifier le projet')

@section('content')
<div class="max-w-3xl space-y-6" x-data="{ lightboxUrl: '' }">
    {{-- Basic info form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-5">Informations générales</h3>
        <form method="POST" action="{{ route('professor.projects.update', $project) }}">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Étudiant</label>
                    <select name="user_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $project->user_id == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Titre *</label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Sans catégorie --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Statut</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="blocked" {{ $project->status === 'blocked' ? 'selected' : '' }}>Bloqué</option>
                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" id="description" rows="8"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $project->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex gap-3 mt-5 pt-5 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">Enregistrer</button>
                <a href="{{ route('professor.projects.show', $project) }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Attachments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data>
        <h3 class="font-semibold text-gray-800 mb-4">Fichiers joints</h3>
        @if($project->attachments->count())
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-4">
            @foreach($project->attachments as $attachment)
            <div class="relative group">
                @if($attachment->isImage())
                <div class="cursor-pointer" @click="lightboxUrl = '{{ Storage::url($attachment->path) }}'">
                    <img src="{{ Storage::url($attachment->path) }}" alt="{{ $attachment->original_name }}"
                         class="w-full h-24 object-cover rounded-xl border border-gray-200 group-hover:opacity-90 transition-opacity">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                        <div class="p-1.5 bg-white/80 rounded-full shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </div>
                    </div>
                </div>
                @else
                <div class="w-full h-24 bg-gray-100 rounded-xl border border-gray-200 flex flex-col items-center justify-center gap-1 text-gray-400 text-xs px-2 text-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="truncate max-w-full px-1">{{ $attachment->original_name }}</span>
                </div>
                @endif
                <form method="POST" action="{{ route('professor.projects.attachments.destroy', [$project, $attachment]) }}"
                      onsubmit="return confirm('Supprimer ce fichier ?')"
                      class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('professor.projects.attachments.store', $project) }}" enctype="multipart/form-data" class="flex gap-3 items-center">
            @csrf
            <input type="file" name="attachment" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx"
                   class="flex-1 text-sm text-gray-600 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-green-100 file:text-green-700 file:text-xs file:cursor-pointer">
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors whitespace-nowrap">Ajouter</button>
        </form>
    </div>

    {{-- Lightbox --}}
    <div x-show="lightboxUrl" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black/90 flex items-center justify-center z-[200] p-4 cursor-pointer" @click="lightboxUrl = ''" @keydown.escape.window="lightboxUrl = ''">
        <button class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="lightboxUrl" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
    </div>
</div>

@push('scripts')
<script>
const easyMDE = new EasyMDE({
    element: document.getElementById('description'),
    spellChecker: false,
    autosave: { enabled: false },
    toolbar: ['bold','italic','heading','|','quote','unordered-list','ordered-list','|','link','image','|','preview','guide'],
});
</script>
@endpush
@endsection
