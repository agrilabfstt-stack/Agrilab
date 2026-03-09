@extends('layouts.student')

@section('title', 'Nouveau projet')
@section('page-title', 'Nouveau projet')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <div>
                <h3 class="font-semibold text-gray-800">Créer un nouveau projet</h3>
                <p class="text-xs text-gray-500">Remplissez les informations, puis vous pourrez ajouter des sections de données.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('student.projects.store') }}" class="p-6">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Titre du projet <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Ex : Impact de l'irrigation goutte-à-goutte"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                        <option value="">— Sans catégorie —</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" id="description" rows="8"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-7 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Créer et continuer
                </button>
                <a href="{{ route('student.projects.index') }}"
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
    placeholder: 'Décrivez votre projet en Markdown...',
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
