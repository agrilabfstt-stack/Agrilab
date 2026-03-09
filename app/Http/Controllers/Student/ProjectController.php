<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\ProjectData;
use App\Models\ProjectDataFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    private function ownProject(int $id): Project
    {
        return Project::where('user_id', Auth::id())->findOrFail($id);
    }

    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('student.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('student.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'status' => 'active',
        ]);

        return redirect()->route('student.projects.edit', $project)
            ->with('success', 'Projet créé ! Ajoutez maintenant vos sections de données.');
    }

    public function show(int $id)
    {
        $project = $this->ownProject($id);
        $project->load('category', 'data.files', 'attachments', 'comments.user');
        return view('student.projects.show', compact('project'));
    }

    public function edit(int $id)
    {
        $project = $this->ownProject($id);

        if ($project->isBlocked()) {
            return redirect()->route('student.projects.show', $project)
                ->with('error', 'Ce projet est bloqué et ne peut pas être modifié.');
        }

        $project->load('data.files', 'attachments', 'category');
        $categories = Category::orderBy('name')->get();

        return view('student.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $project = $this->ownProject($id);

        if ($project->isBlocked()) {
            return back()->with('error', 'Ce projet est bloqué.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $project->update($request->only('title', 'description', 'category_id'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Projet mis à jour.']);
        }

        return redirect()->route('student.projects.edit', $project)
            ->with('success', 'Informations du projet mises à jour.');
    }

    public function destroy(int $id)
    {
        $project = $this->ownProject($id);

        foreach ($project->data as $data) {
            foreach ($data->files as $file) {
                Storage::disk('public')->delete($file->path);
            }
        }

        foreach ($project->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }

        $project->delete();

        return redirect()->route('student.projects.index')
            ->with('success', 'Projet supprimé avec succès.');
    }

    // --- Data Sections ---

    public function storeData(Request $request, int $id)
    {
        $project = $this->ownProject($id);

        if ($project->isBlocked()) {
            return back()->with('error', 'Ce projet est bloqué.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:image,value,table,chart'],
        ]);

        $content = null;

        if ($request->type === 'value') {
            $content = ['value' => $request->input('value', '')];
        } elseif ($request->type === 'table') {
            $columns = $request->input('table_columns', []);
            $rows    = $request->input('table_rows', []);
            if (is_string($columns)) $columns = json_decode($columns, true) ?? [];
            if (is_string($rows))    $rows    = json_decode($rows,    true) ?? [];
            $content = ['columns' => $columns, 'rows' => $rows];
        } elseif ($request->type === 'chart') {
            $labels   = $request->input('labels', []);
            $datasets = $request->input('datasets', []);
            if (is_string($labels))   $labels   = json_decode($labels,   true) ?? [];
            if (is_string($datasets)) $datasets = json_decode($datasets, true) ?? [];
            // Handle simple chart creation from create modal
            if (empty($datasets) && $request->filled('dataset_label')) {
                $data_values = array_map('floatval', array_filter(array_map('trim', explode(',', $request->input('dataset_data', '')))));
                $labels_arr  = array_map('trim', array_filter(explode(',', $request->input('labels', ''))));
                $datasets    = [['label' => $request->input('dataset_label', 'Données'), 'data' => $data_values]];
                $labels      = $labels_arr;
            }
            $content = [
                'chart_type' => $request->input('chart_type', 'bar'),
                'labels'     => $labels,
                'datasets'   => $datasets,
            ];
        }

        $sortOrder = $project->data()->max('sort_order') + 1;

        $data = $project->data()->create([
            'name' => $request->name,
            'type' => $request->type,
            'content' => $content,
            'sort_order' => $sortOrder,
        ]);

        if ($request->type === 'image' && $request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('project-data-images', 'public');
                $data->files()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Section "' . $request->name . '" ajoutée avec succès.');
    }

    public function updateData(Request $request, int $id, ProjectData $data)
    {
        $project = $this->ownProject($id);
        abort_if($data->project_id !== $project->id, 403);

        if ($project->isBlocked()) {
            if ($request->wantsJson()) return response()->json(['message' => 'Projet bloqué.'], 403);
            return back()->with('error', 'Ce projet est bloqué.');
        }

        $request->validate(['name' => ['required', 'string', 'max:255']]);

        $content = $data->content;

        if ($data->type === 'value') {
            $content = ['value' => $request->input('value', '')];
        } elseif ($data->type === 'table') {
            $columns = $request->input('table_columns', []);
            $rows    = $request->input('table_rows', []);
            if (is_string($columns)) $columns = json_decode($columns, true) ?? [];
            if (is_string($rows))    $rows    = json_decode($rows,    true) ?? [];
            $content = ['columns' => $columns, 'rows' => $rows];
        } elseif ($data->type === 'chart') {
            $labels   = $request->input('labels', []);
            $datasets = $request->input('datasets', []);
            if (is_string($labels))   $labels   = json_decode($labels,   true) ?? [];
            if (is_string($datasets)) $datasets = json_decode($datasets, true) ?? [];
            $content = [
                'chart_type' => $request->input('chart_type', 'bar'),
                'labels'     => $labels,
                'datasets'   => $datasets,
            ];
        }

        $data->update(['name' => $request->name, 'content' => $content]);

        if ($data->type === 'image' && $request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('project-data-images', 'public');
                $data->files()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Section mise à jour.']);
        }

        return back()->with('success', 'Section mise à jour.');
    }

    public function destroyData(int $id, ProjectData $data)
    {
        $project = $this->ownProject($id);
        abort_if($data->project_id !== $project->id, 403);

        foreach ($data->files as $file) {
            Storage::disk('public')->delete($file->path);
        }
        $data->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Section supprimée.']);
        }

        return back()->with('success', 'Section supprimée.');
    }

    public function destroyDataFile(int $id, ProjectData $data, ProjectDataFile $file)
    {
        $project = $this->ownProject($id);
        abort_if($data->project_id !== $project->id, 403);
        abort_if($file->project_data_id !== $data->id, 403);

        Storage::disk('public')->delete($file->path);
        $file->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Image supprimée.']);
        }

        return back()->with('success', 'Image supprimée.');
    }

    // --- Attachments ---

    public function storeAttachment(Request $request, int $id)
    {
        $project = $this->ownProject($id);

        if ($project->isBlocked()) {
            return back()->with('error', 'Ce projet est bloqué.');
        }

        $request->validate([
            'attachment' => ['required', 'file', 'max:51200'],
        ]);

        foreach ([$request->file('attachment')] as $file) {
            $mime = $file->getMimeType();
            $fileType = str_starts_with($mime, 'image/') ? 'image'
                : (str_starts_with($mime, 'video/') ? 'video' : 'document');

            $path = $file->store('project-attachments', 'public');

            $project->attachments()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $mime,
                'file_type' => $fileType,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Fichier ajouté.']);
        }

        return back()->with('success', 'Fichier(s) ajouté(s) avec succès.');
    }

    public function destroyAttachment(int $id, ProjectAttachment $attachment)
    {
        $project = $this->ownProject($id);
        abort_if($attachment->project_id !== $project->id, 403);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Fichier supprimé.']);
        }

        return back()->with('success', 'Fichier supprimé.');
    }
}
