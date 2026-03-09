@extends('layouts.professor')

@section('title', 'Créer un projet')
@section('page-title', 'Créer un projet')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('professor.projects.store') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Étudiant *</label>
                    <select name="user_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('user_id') border-red-400 @enderror">
                        <option value="">-- Sélectionner un étudiant --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Titre du projet *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Ex: Étude de l'impact des engrais biologiques"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Sans catégorie --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description du projet *</label>
                    <textarea name="description" id="description" rows="8"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-7 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                    Créer le projet
                </button>
                <a href="{{ route('professor.projects.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const easyMDE = new EasyMDE({
        element: document.getElementById('description'),
        spellChecker: false,
        autosave: { enabled: false },
        placeholder: 'Rédigez la description du projet en Markdown...',
        toolbar: ['bold','italic','heading','|','quote','unordered-list','ordered-list','|','link','image','|','preview','guide'],
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!easyMDE.value().trim()) {
            e.preventDefault();
            easyMDE.codemirror.getWrapperElement().style.outline = '2px solid #ef4444';
            easyMDE.codemirror.getWrapperElement().scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
@endsection
