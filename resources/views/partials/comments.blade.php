{{-- partials/comments.blade.php --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="commentsManager()">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <h3 class="font-semibold text-gray-800">Commentaires</h3>
        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" x-text="commentCount">{{ $project->comments->count() }}</span>
    </div>

    {{-- Toast --}}
    <div x-show="toast.show" x-transition :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
         class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-[100] flex items-center gap-3 text-sm font-medium" x-cloak>
        <span x-text="toast.message"></span>
    </div>

    <div class="p-6">
        {{-- Add comment form — on top --}}
        @auth
        <form @submit.prevent="submitComment()" class="mb-6">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold text-white
                    @if(auth()->user()->isProfessor()) bg-blue-500 @elseif(auth()->user()->isAdmin()) bg-gray-600 @else bg-green-500 @endif">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 relative">
                    <textarea x-model="newComment" rows="2" placeholder="Écrire un commentaire..."
                              class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none transition-shadow"
                              @keydown.meta.enter="submitComment()" @keydown.ctrl.enter="submitComment()"></textarea>
                    <button type="submit" :disabled="!newComment.trim() || sending"
                            class="absolute right-2 bottom-2 p-2 bg-green-600 hover:bg-green-700 disabled:opacity-40 disabled:hover:bg-green-600 text-white rounded-xl transition-colors">
                        <svg x-show="!sending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5 ml-12">Ctrl+Entrée pour envoyer</p>
        </form>
        @endauth

        {{-- Comment list — newest first --}}
        <div class="space-y-4" id="comments-list">
            @forelse($project->comments->sortByDesc('created_at') as $comment)
            <div class="flex gap-3 @if($comment->user_id === auth()->id()) flex-row-reverse @endif" id="comment-{{ $comment->id }}">
                <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold text-white
                    @if($comment->user->isProfessor()) bg-blue-500 @elseif($comment->user->isAdmin()) bg-gray-600 @else bg-green-500 @endif">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 max-w-lg @if($comment->user_id === auth()->id()) flex flex-col items-end @endif">
                    <div class="@if($comment->user_id === auth()->id()) bg-green-600 text-white @else bg-gray-100 text-gray-800 @endif rounded-2xl px-4 py-3 text-sm leading-relaxed">
                        {{ $comment->content }}
                    </div>
                    <div class="flex items-center gap-2 mt-1.5 @if($comment->user_id === auth()->id()) flex-row-reverse @endif">
                        <span class="text-xs text-gray-400">
                            {{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}
                        </span>
                        @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                        <button @click="deleteComment({{ $comment->id }})" class="text-gray-300 hover:text-red-500 transition-colors p-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400 text-sm" id="no-comments">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Aucun commentaire. Soyez le premier à commenter.
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function commentsManager() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    return {
        newComment: '',
        sending: false,
        commentCount: {{ $project->comments->count() }},
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') { this.toast = { show: true, message: msg, type }; setTimeout(() => this.toast.show = false, 3000); },
        async submitComment() {
            if (!this.newComment.trim() || this.sending) return;
            this.sending = true;
            try {
                const fd = new FormData();
                fd.append('content', this.newComment);
                const resp = await fetch('{{ route("comments.store", $project) }}', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd
                });
                if (!resp.ok) throw new Error('Erreur');
                this.newComment = '';
                this.showToast('Commentaire ajouté');
                setTimeout(() => location.reload(), 600);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.sending = false; }
        },
        async deleteComment(id) {
            if (!confirm('Supprimer ce commentaire ?')) return;
            try {
                const fd = new FormData(); fd.append('_method', 'DELETE');
                const resp = await fetch('/comments/' + id, {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd
                });
                if (!resp.ok) throw new Error('Erreur');
                const el = document.getElementById('comment-' + id);
                if (el) el.remove();
                this.commentCount--;
                this.showToast('Commentaire supprimé');
            } catch (e) { this.showToast(e.message, 'error'); }
        }
    }
}
</script>
@endpush
