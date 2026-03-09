@extends('layouts.admin')

@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('content')
<div x-data="categoriesManager()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Toast --}}
    <div x-show="toast.show" x-transition :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
         class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-[100] flex items-center gap-3 text-sm font-medium" x-cloak>
        <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span x-text="toast.message"></span>
    </div>

    {{-- Category list --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <h3 class="font-semibold text-gray-800">Liste des catégories</h3>
                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" x-text="categories.length">{{ $categories->count() }}</span>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                <template x-for="cat in categories" :key="cat.id">
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="w-4 h-4 rounded-full flex-shrink-0" :style="'background-color:' + cat.color"></div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm" x-text="cat.name"></p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate" x-show="cat.description" x-text="cat.description"></p>
                        </div>
                        <span class="text-xs text-gray-400" x-text="cat.projects_count + ' projet(s)'"></span>
                        <div class="flex gap-1.5 flex-shrink-0">
                            <button @click="openEdit(cat)" class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button @click="deleteCategory(cat.id)" class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
                <template x-if="categories.length === 0">
                    <div class="px-5 py-12 text-center text-gray-400 text-sm">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Aucune catégorie pour le moment.
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Sidebar forms --}}
    <div class="space-y-5">
        {{-- Create form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-show="!editMode">
            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <h3 class="font-semibold text-gray-800 text-sm">Nouvelle catégorie</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nom <span class="text-red-400">*</span></label>
                    <input type="text" x-model="createForm.name" placeholder="Ex : Agronomie"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <textarea x-model="createForm.description" rows="2" placeholder="Description optionnelle..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none transition-shadow"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Couleur</label>
                    <div class="flex items-center gap-3">
                        <input type="color" x-model="createForm.color"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                        <div class="flex gap-1.5 flex-wrap">
                            @foreach(['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'] as $color)
                            <div class="w-6 h-6 rounded-full cursor-pointer hover:scale-110 transition-transform border-2 border-transparent hover:border-gray-300"
                                 style="background-color: {{ $color }}"
                                 @click="createForm.color = '{{ $color }}'"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button @click="storeCategory()" :disabled="saving"
                        class="w-full py-2.5 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors">
                    <span x-text="saving ? 'Création...' : 'Créer la catégorie'"></span>
                </button>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-200 overflow-hidden" x-show="editMode" x-cloak>
            <div class="px-5 py-4 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <h3 class="font-semibold text-gray-800 text-sm">Modifier la catégorie</h3>
                </div>
                <button @click="editMode = false" class="p-1 rounded-lg hover:bg-blue-100 text-gray-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nom <span class="text-red-400">*</span></label>
                    <input type="text" x-model="editForm.name"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <textarea x-model="editForm.description" rows="2"
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none transition-shadow"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Couleur</label>
                    <div class="flex items-center gap-3">
                        <input type="color" x-model="editForm.color"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                        <div class="flex gap-1.5 flex-wrap">
                            @foreach(['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'] as $color)
                            <div class="w-6 h-6 rounded-full cursor-pointer hover:scale-110 transition-transform border-2 border-transparent hover:border-gray-300"
                                 style="background-color: {{ $color }}"
                                 @click="editForm.color = '{{ $color }}'"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button @click="updateCategory()" :disabled="saving"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors">
                        <span x-text="saving ? 'Enregistrement...' : 'Enregistrer'"></span>
                    </button>
                    <button @click="editMode = false"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function categoriesManager() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    return {
        categories: @json($categories),
        editMode: false,
        saving: false,
        toast: { show: false, message: '', type: 'success' },
        createForm: { name: '', description: '', color: '#10b981' },
        editForm: { id: null, name: '', description: '', color: '#10b981' },
        showToast(msg, type = 'success') { this.toast = { show: true, message: msg, type }; setTimeout(() => this.toast.show = false, 3000); },
        openEdit(cat) {
            this.editForm = { id: cat.id, name: cat.name, description: cat.description || '', color: cat.color || '#10b981' };
            this.editMode = true;
        },
        async storeCategory() {
            if (!this.createForm.name.trim()) { this.showToast('Le nom est requis', 'error'); return; }
            this.saving = true;
            try {
                const resp = await fetch('{{ route("admin.categories.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(this.createForm)
                });
                const data = await resp.json().catch(() => ({}));
                if (!resp.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(', ') || 'Erreur');
                this.categories.unshift({ ...data.category, projects_count: 0 });
                this.createForm = { name: '', description: '', color: '#10b981' };
                this.showToast('Catégorie créée');
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.saving = false; }
        },
        async updateCategory() {
            if (!this.editForm.name.trim()) { this.showToast('Le nom est requis', 'error'); return; }
            this.saving = true;
            try {
                const resp = await fetch('/admin/categories/' + this.editForm.id, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: this.editForm.name, description: this.editForm.description, color: this.editForm.color })
                });
                const data = await resp.json().catch(() => ({}));
                if (!resp.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(', ') || 'Erreur');
                const idx = this.categories.findIndex(c => c.id === this.editForm.id);
                if (idx !== -1) { this.categories[idx].name = this.editForm.name; this.categories[idx].description = this.editForm.description; this.categories[idx].color = this.editForm.color; }
                this.editMode = false;
                this.showToast('Catégorie mise à jour');
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.saving = false; }
        },
        async deleteCategory(id) {
            if (!confirm('Supprimer cette catégorie ?')) return;
            try {
                const resp = await fetch('/admin/categories/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                });
                if (!resp.ok) throw new Error('Erreur');
                this.categories = this.categories.filter(c => c.id !== id);
                if (this.editMode && this.editForm.id === id) this.editMode = false;
                this.showToast('Catégorie supprimée');
            } catch (e) { this.showToast(e.message, 'error'); }
        }
    }
}
</script>
@endpush
@endsection
