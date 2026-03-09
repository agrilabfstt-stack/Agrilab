<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        $search = $request->get('search', '');

        $query = User::whereIn('role', ['professor', 'student']);

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with('professor')->withCount('projects')->latest()->paginate(15);
        $professors = User::where('role', 'professor')->orderBy('name')->get();

        $counts = [
            'all'        => User::whereIn('role', ['professor', 'student'])->count(),
            'professors' => User::where('role', 'professor')->count(),
            'students'   => User::where('role', 'student')->count(),
        ];

        return view('admin.users.index', compact('users', 'professors', 'role', 'search', 'counts'));
    }

    public function create()
    {
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        return view('admin.users.create', compact('professors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['professor', 'student'])],
            'professor_id' => ['nullable', Rule::requiredIf($request->role === 'student'), 'exists:users,id'],
        ]);

        $data['password'] = Hash::make($data['password']);
        if ($data['role'] === 'professor') {
            $data['professor_id'] = null;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de modifier l\'administrateur.');
        }
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'professors'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de modifier l\'administrateur.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['professor', 'student'])],
            'professor_id' => ['nullable', Rule::requiredIf($request->role === 'student'), 'exists:users,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($data['role'] === 'professor') {
            $data['professor_id'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer l\'administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
