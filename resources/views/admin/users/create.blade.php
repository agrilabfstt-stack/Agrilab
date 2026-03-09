@extends('layouts.admin')

@section('title', 'Créer un utilisateur')
@section('page-title', 'Créer un utilisateur')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" x-data="userForm()">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Rôle</label>
                    <select name="role" x-model="role" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('role') border-red-400 @enderror">
                        <option value="">-- Sélectionner un rôle --</option>
                        <option value="professor" {{ old('role') === 'professor' ? 'selected' : '' }}>Professeur</option>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Étudiant</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-show="role === 'student'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Professeur responsable</label>
                    <select name="professor_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('professor_id') border-red-400 @enderror">
                        <option value="">-- Sans professeur --</option>
                        @foreach($professors as $prof)
                        <option value="{{ $prof->id }}" {{ old('professor_id') == $prof->id ? 'selected' : '' }}>
                            {{ $prof->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('professor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-7 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                    Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function userForm() {
    return {
        role: '{{ old('role', '') }}'
    }
}
</script>
@endpush
@endsection
