@extends('layouts.app')

@section('title', 'Proposer une idée — Agrilab')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="createIdea()">
    <a href="{{ route('ideas.index') }}" class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-800 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux idées
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
            <h1 class="text-xl font-bold text-gray-900">💡 Proposer une nouvelle idée</h1>
            <p class="text-sm text-gray-500 mt-1">Décrivez votre idée de projet expérimental. D'autres utilisateurs pourront la commenter et la rejoindre.</p>
        </div>

        <form @submit.prevent="submitIdea()" class="p-6 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Titre *</label>
                <input type="text" id="title" x-model="form.title" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Ex : Étude de l'impact de l'irrigation par goutte-à-goutte...">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description * (Markdown supporté)</label>
                <textarea id="description" x-model="form.description" required rows="8"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                          placeholder="Décrivez votre idée en détail. Vous pouvez utiliser le format Markdown."></textarea>
            </div>

            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-1.5">Tags (séparés par des virgules)</label>
                <input type="text" id="tags" x-model="form.tags"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="irrigation, sol, cultures, agronomie">
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1.5">Pièce jointe (optionnel)</label>
                <input type="file" id="attachment" @change="form.attachment = $event.target.files[0]"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" :disabled="sending"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                    <svg x-show="!sending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="sending ? 'Envoi...' : 'Publier l\'idée'"></span>
                </button>
                <a href="{{ route('ideas.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition-colors">Annuler</a>
            </div>

            <div x-show="error" x-cloak class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <span x-text="error"></span>
            </div>
        </form>
    </div>

    {{-- Toast --}}
    <div x-show="toast.show" x-transition x-cloak :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
         class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-[100] text-sm font-medium">
        <span x-text="toast.message"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createIdea() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    return {
        form: { title: '', description: '', tags: '', attachment: null },
        sending: false,
        error: '',
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') {
            this.toast = { show: true, message: msg, type };
            setTimeout(() => this.toast.show = false, 3000);
        },
        async submitIdea() {
            if (!this.form.title.trim() || !this.form.description.trim()) {
                this.error = 'Le titre et la description sont obligatoires.';
                return;
            }
            this.sending = true;
            this.error = '';
            try {
                const fd = new FormData();
                fd.append('title', this.form.title);
                fd.append('description', this.form.description);
                if (this.form.tags) fd.append('tags', this.form.tags);
                if (this.form.attachment) fd.append('attachment', this.form.attachment);

                const resp = await fetch('{{ route("ideas.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await resp.json();
                if (!resp.ok) {
                    const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Erreur');
                    throw new Error(errors);
                }
                this.showToast('Idée créée avec succès !');
                setTimeout(() => window.location.href = '{{ route("ideas.index") }}', 800);
            } catch (e) {
                this.error = e.message;
            } finally {
                this.sending = false;
            }
        }
    }
}
</script>
@endpush
