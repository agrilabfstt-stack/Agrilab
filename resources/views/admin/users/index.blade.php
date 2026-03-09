@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Utilisateurs')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
    <div class="flex gap-2">
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('role') ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Tous ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'professor']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('role') === 'professor' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Professeurs ({{ $counts['professors'] }})
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('role') === 'student' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Étudiants ({{ $counts['students'] }})
        </a>
    </div>
    <div class="flex gap-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
            @if(request('role')) <input type="hidden" name="role" value="{{ request('role') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher..."
                   class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-52">
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm transition-colors">
                Rechercher
            </button>
        </form>
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Professeur</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Projets</th>
                <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white {{ $user->role === 'professor' ? 'bg-blue-500' : 'bg-green-500' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $user->role === 'professor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ $user->role === 'professor' ? 'Professeur' : 'Étudiant' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500 hidden md:table-cell">
                    {{ $user->professor?->name ?? ($user->role === 'student' ? '<span class="text-orange-500 text-xs">Non assigné</span>' : '—') }}
                </td>
                <td class="px-6 py-4 text-gray-500 hidden lg:table-cell">
                    {{ $user->projects_count ?? 0 }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors" title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Supprimer « {{ $user->name }} » ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-1.5 rounded-lg hover:bg-red-50 text-red-500 transition-colors" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    Aucun utilisateur trouvé.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
