<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ShowcaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('is_showcased', true)
            ->with(['user.professor', 'category', 'attachments', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('professor')) {
            $query->where('created_by', $request->professor);
        }

        $projects = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        $years = Project::where('is_showcased', true)
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('showcase.index', compact('projects', 'categories', 'professors', 'years'));
    }

    public function show(Project $project)
    {
        if (!$project->is_showcased) {
            abort(404);
        }

        $project->load(['user.professor', 'category', 'data.files', 'attachments', 'comments.user', 'creator']);

        return view('showcase.show', compact('project'));
    }

    public function toggleShowcase(Request $request, Project $project)
    {
        $project->update(['is_showcased' => !$project->is_showcased]);

        return response()->json([
            'message' => $project->is_showcased ? 'Projet publié dans le Showcase.' : 'Projet retiré du Showcase.',
            'is_showcased' => $project->is_showcased,
        ]);
    }
}
