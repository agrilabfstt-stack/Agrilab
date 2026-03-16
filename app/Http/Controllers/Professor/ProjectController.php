<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectData;
use App\Models\ProjectDataFile;
use App\Models\ProjectAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    private function professorProjects()
    {
        $professor = Auth::user();
        return Project::where(function ($q) use ($professor) {
            $q->whereHas('user', function ($sq) use ($professor) {
                $sq->where('professor_id', $professor->id);
            })->orWhere('created_by', $professor->id);
        });
    }

    public function index(Request $request)
    {
        $professor = Auth::user();
        $students = User::where('professor_id', $professor->id)->orderBy('name')->get();

        $query = $this->professorProjects()->with('user', 'category');

        if ($request->filled('student_id')) {
            $query->where('user_id', $request->student_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $projects = $query->latest()->paginate(12);

        return view('professor.projects.index', compact('projects', 'students'));
    }

    public function create()
    {
        $professor = Auth::user();
        $students = User::where('professor_id', $professor->id)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('professor.projects.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $professor = Auth::user();
        $student = User::find($request->user_id);

        if ($student->professor_id !== $professor->id) {
            abort(403, 'Cet étudiant ne vous appartient pas.');
        }

        $project = Project::create([
            'title'       => $request->title,
            'description' => $request->description ?? '',
            'user_id'     => $student->id,
            'created_by'  => $professor->id,
            'category_id' => $request->category_id,
            'status'      => 'active',
        ]);

        return redirect()->route('professor.projects.edit', $project)
            ->with('success', 'Projet créé. Vous pouvez maintenant ajouter des sections.');
    }

    public function show(Project $project)
    {
        $this->authorizeProject($project);
        $project->load('user', 'category', 'data.files', 'attachments', 'comments.user');
        return view('professor.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);
        $project->load('data.files', 'attachments', 'category');
        $categories = Category::orderBy('name')->get();
        return view('professor.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:active,blocked,completed'],
        ]);

        $project->update($request->only('title', 'description', 'category_id', 'status'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Projet mis à jour.', 'project' => $project]);
        }

        return redirect()->route('professor.projects.show', $project)
            ->with('success', 'Projet mis à jour.');
    }

    public function toggleStatus(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        if ($request->filled('status')) {
            $request->validate(['status' => ['required', 'in:active,blocked,completed']]);
            $project->update(['status' => $request->status]);
        } else {
            // Toggle between active and blocked
            $project->update([
                'status' => $project->status === 'blocked' ? 'active' : 'blocked',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Statut mis à jour.', 'status' => $project->status]);
        }

        return back()->with('success', 'Statut du projet mis à jour.');
    }

    // --- Data Sections ---

    public function storeData(Request $request, Project $project)
    {
        $this->authorizeProject($project);
        return $this->saveDataSection($request, $project);
    }

    public function updateData(Request $request, Project $project, ProjectData $data)
    {
        $this->authorizeProject($project);
        return $this->updateDataSection($request, $project, $data);
    }

    public function destroyData(Request $request, Project $project, ProjectData $data)
    {
        $this->authorizeProject($project);
        $this->deleteDataSection($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Section supprimée.']);
        }

        return back()->with('success', 'Section supprimée.');
    }

    // --- Attachments ---

    public function storeAttachment(Request $request, Project $project)
    {
        $this->authorizeProject($project);
        return $this->saveAttachment($request, $project);
    }

    public function destroyAttachment(Request $request, Project $project, ProjectAttachment $attachment)
    {
        $this->authorizeProject($project);
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Fichier supprimé.']);
        }

        return back()->with('success', 'Fichier supprimé.');
    }

    // --- Shared helpers ---

    protected function authorizeProject(Project $project): void
    {
        $professor = Auth::user();
        $allowed = (int) $project->created_by === (int) $professor->id
            || ($project->user && (int) $project->user->professor_id === (int) $professor->id);

        if (! $allowed) {
            abort(403);
        }
    }

    protected function saveDataSection(Request $request, Project $project)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:image,value,table,chart'],
        ]);

        $content = null;

        if ($request->type === 'value') {
            $content = ['value' => $request->input('value', '')];
        } elseif ($request->type === 'table') {
            $columns = $request->input('table_columns', ['Colonne 1']);
            $rows    = $request->input('table_rows', [['']]);
            if (is_string($columns)) $columns = json_decode($columns, true) ?? [];
            if (is_string($rows))    $rows    = json_decode($rows,    true) ?? [];
            $content = ['columns' => $columns, 'rows' => $rows];
        } elseif ($request->type === 'chart') {
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

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Section ajoutée.', 'data' => $data->load('files')], 201);
        }

        return back()->with('success', 'Section ajoutée avec succès.');
    }

    protected function updateDataSection(Request $request, Project $project, ProjectData $data)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

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
            return response()->json(['message' => 'Section mise à jour.', 'data' => $data->load('files')]);
        }

        return back()->with('success', 'Section mise à jour.');
    }

    protected function deleteDataSection(ProjectData $data): void
    {
        foreach ($data->files as $file) {
            Storage::disk('public')->delete($file->path);
        }
        $data->delete();
    }

    protected function saveAttachment(Request $request, Project $project)
    {
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
            return response()->json(['message' => 'Fichier ajouté.'], 201);
        }

        return back()->with('success', 'Fichier(s) ajouté(s) avec succès.');
    }
}
