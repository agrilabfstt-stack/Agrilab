@extends('layouts.app')

@section('title', $idea->title . ' — Idées Agrilab')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ideaDetail()">

    <a href="{{ route('ideas.index') }}" class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-800 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux idées
    </a>

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4 justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $idea->title }}</h1>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                              :class="{
                                  'bg-green-100 text-green-700': currentStatus === 'open',
                                  'bg-yellow-100 text-yellow-700': currentStatus === 'in_progress',
                                  'bg-blue-100 text-blue-700': currentStatus === 'completed'
                              }"
                              x-text="statusLabels[currentStatus]">
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $idea->author->name ?? '—' }}
                            <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">{{ $idea->author_type === 'professor' ? 'Professeur' : 'Étudiant' }}</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $idea->created_at->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span x-text="participantCount + ' participant(s)'"></span>
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-2 flex-shrink-0">
                    @auth
                    {{-- Join/Leave --}}
                    <button @click="hasJoined ? leaveIdea() : joinIdea()"
                            :disabled="actionSending"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors"
                            :class="hasJoined ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-600 text-white hover:bg-green-700'">
                        <svg x-show="!hasJoined" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <svg x-show="hasJoined" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/></svg>
                        <span x-text="hasJoined ? 'Quitter' : 'Rejoindre ce projet'"></span>
                    </button>

                    @if(auth()->user()->isProfessor() || auth()->user()->isAdmin())
                    {{-- Status change --}}
                    <select x-model="currentStatus" @change="updateStatus()"
                            class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="open">Ouvert</option>
                        <option value="in_progress">En cours</option>
                        <option value="completed">Terminé</option>
                    </select>

                    {{-- Convert to project --}}
                    <button @click="convertToProject()" :disabled="actionSending"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Convertir en projet
                    </button>
                    @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Tags --}}
        @if($idea->tags && count($idea->tags))
        <div class="px-6 py-3 border-t border-gray-100 flex flex-wrap gap-2">
            @foreach($idea->tags as $tag)
            <span class="text-xs px-3 py-1 bg-blue-50 text-blue-600 rounded-full font-medium">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Description --}}
        <div class="px-6 pb-6 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-sm font-semibold text-gray-700">Description</h3>
            </div>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! \Illuminate\Support\Str::markdown($idea->description ?? '') !!}
            </div>
        </div>

        {{-- Attachment --}}
        @if($idea->attachment_path)
        <div class="px-6 pb-6 border-t border-gray-100 pt-4">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <h3 class="text-sm font-semibold text-gray-700">Pièce jointe</h3>
            </div>
            <a href="{{ Storage::url($idea->attachment_path) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm text-gray-700 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ $idea->attachment_name }}
            </a>
        </div>
        @endif
    </div>

    {{-- Participants --}}
    @if($idea->participants->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <h3 class="font-semibold text-gray-800">Participants</h3>
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" x-text="participantCount"></span>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3" id="participants-list">
                @foreach($idea->participants as $participant)
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white
                        @if($participant->user->isProfessor()) bg-blue-500 @elseif($participant->user->isAdmin()) bg-gray-600 @else bg-green-500 @endif">
                        {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-700">{{ $participant->user->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Comments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <h3 class="font-semibold text-gray-800">Discussion</h3>
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" x-text="commentCount">{{ $idea->comments->count() }}</span>
        </div>

        <div class="p-6">
            {{-- Add comment --}}
            @auth
            <form @submit.prevent="submitComment()" class="mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold text-white
                        @if(auth()->user()->isProfessor()) bg-blue-500 @elseif(auth()->user()->isAdmin()) bg-gray-600 @else bg-green-500 @endif">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 relative">
                        <textarea x-model="newComment" rows="2" placeholder="Écrire un commentaire..."
                                  class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none transition-shadow"
                                  @keydown.ctrl.enter="submitComment()"></textarea>
                        <button type="submit" :disabled="!newComment.trim() || commentSending"
                                class="absolute right-2 bottom-2 p-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-40 text-white rounded-xl transition-colors">
                            <svg x-show="!commentSending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <svg x-show="commentSending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 ml-12">Ctrl+Entrée pour envoyer</p>
            </form>
            @endauth

            {{-- Comments list --}}
            <div class="space-y-4" id="idea-comments-list">
                @forelse($idea->comments->sortByDesc('created_at') as $comment)
                <div class="flex gap-3 @if(auth()->check() && $comment->user_id === auth()->id()) flex-row-reverse @endif" id="idea-comment-{{ $comment->id }}">
                    <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold text-white
                        @if($comment->user->isProfessor()) bg-blue-500 @elseif($comment->user->isAdmin()) bg-gray-600 @else bg-green-500 @endif">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 max-w-lg @if(auth()->check() && $comment->user_id === auth()->id()) flex flex-col items-end @endif">
                        <div class="@if(auth()->check() && $comment->user_id === auth()->id()) bg-blue-600 text-white @else bg-gray-100 text-gray-800 @endif rounded-2xl px-4 py-3 text-sm leading-relaxed">
                            {{ $comment->content }}
                        </div>
                        <div class="flex items-center gap-2 mt-1.5 @if(auth()->check() && $comment->user_id === auth()->id()) flex-row-reverse @endif">
                            <span class="text-xs text-gray-400">
                                {{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}
                            </span>
                            @auth
                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                            <button @click="deleteComment({{ $comment->id }})" class="text-gray-300 hover:text-red-500 transition-colors p-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-sm" id="no-idea-comments">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Aucun commentaire. Soyez le premier à discuter de cette idée.
                </div>
                @endforelse
            </div>
        </div>
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
function ideaDetail() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    return {
        hasJoined: {{ $hasJoined ? 'true' : 'false' }},
        currentStatus: '{{ $idea->status }}',
        participantCount: {{ $idea->participants->count() }},
        commentCount: {{ $idea->comments->count() }},
        statusLabels: { open: 'Ouvert', in_progress: 'En cours', completed: 'Terminé' },
        newComment: '',
        commentSending: false,
        actionSending: false,
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') {
            this.toast = { show: true, message: msg, type };
            setTimeout(() => this.toast.show = false, 3000);
        },
        async joinIdea() {
            this.actionSending = true;
            try {
                const resp = await fetch('{{ route("ideas.join", $idea) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                });
                if (!resp.ok) throw new Error((await resp.json()).message || 'Erreur');
                this.hasJoined = true;
                this.participantCount++;
                this.showToast('Vous avez rejoint cette idée !');
                setTimeout(() => location.reload(), 800);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.actionSending = false; }
        },
        async leaveIdea() {
            this.actionSending = true;
            try {
                const resp = await fetch('{{ route("ideas.leave", $idea) }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                });
                if (!resp.ok) throw new Error('Erreur');
                this.hasJoined = false;
                this.participantCount--;
                this.showToast('Vous avez quitté cette idée.');
                setTimeout(() => location.reload(), 800);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.actionSending = false; }
        },
        async updateStatus() {
            try {
                const fd = new FormData();
                fd.append('_method', 'PATCH');
                fd.append('status', this.currentStatus);
                const resp = await fetch('{{ route("ideas.update-status", $idea) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                if (!resp.ok) throw new Error('Erreur');
                this.showToast('Statut mis à jour.');
            } catch (e) { this.showToast(e.message, 'error'); }
        },
        async convertToProject() {
            if (!confirm('Convertir cette idée en projet réel ? Un nouveau projet sera créé automatiquement.')) return;
            this.actionSending = true;
            try {
                const resp = await fetch('{{ route("ideas.convert", $idea) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                });
                if (!resp.ok) throw new Error('Erreur');
                const data = await resp.json();
                this.showToast('Idée convertie en projet !');
                this.currentStatus = 'completed';
                setTimeout(() => {
                    @if(auth()->check() && (auth()->user()->isProfessor()))
                    window.location.href = '/professor/projects/' + data.project_id;
                    @elseif(auth()->check() && auth()->user()->isAdmin())
                    window.location.href = '/admin';
                    @else
                    location.reload();
                    @endif
                }, 1000);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.actionSending = false; }
        },
        async submitComment() {
            if (!this.newComment.trim() || this.commentSending) return;
            this.commentSending = true;
            try {
                const fd = new FormData();
                fd.append('content', this.newComment);
                const resp = await fetch('{{ route("ideas.comments.store", $idea) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                if (!resp.ok) throw new Error('Erreur');
                this.newComment = '';
                this.commentCount++;
                this.showToast('Commentaire ajouté');
                setTimeout(() => location.reload(), 600);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.commentSending = false; }
        },
        async deleteComment(id) {
            if (!confirm('Supprimer ce commentaire ?')) return;
            try {
                const fd = new FormData();
                fd.append('_method', 'DELETE');
                const resp = await fetch('/idea-comments/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                if (!resp.ok) throw new Error('Erreur');
                const el = document.getElementById('idea-comment-' + id);
                if (el) el.remove();
                this.commentCount--;
                this.showToast('Commentaire supprimé');
            } catch (e) { this.showToast(e.message, 'error'); }
        }
    }
}
</script>
@endpush
