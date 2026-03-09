<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('projects')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $category = Category::create($data);

        if ($request->wantsJson()) {
            return response()->json(['category' => $category], 201);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $category->update($data);

        if ($request->wantsJson()) {
            return response()->json(['category' => $category]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Supprimée']);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
