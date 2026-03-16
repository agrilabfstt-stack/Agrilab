<?php

namespace App\Http\Controllers;

use App\Models\IdeaComment;
use App\Models\IdeaParticipant;
use App\Models\Project;
use App\Models\ResearchIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResearchIdeaController extends Controller
{
    public function index(Request $request)
    {
        $query = ResearchIdea::with('author', 'participants');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ideas = $query->latest()->paginate(12);

        return view('ideas.index', compact('ideas'));
    }

    public function create()
    {
        return view('ideas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ]);

        $user = Auth::user();

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => $user->id,
            'author_type' => $user->role,
            'status' => 'open',
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : null,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('idea-attachments', 'public');
            $data['attachment_name'] = $file->getClientOriginalName();
        }

        $idea = ResearchIdea::create($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Idée créée avec succès.', 'idea' => $idea]);
        }

        return redirect()->route('ideas.show', $idea)->with('success', 'Idée créée avec succès.');
    }

    public function show(ResearchIdea $idea)
    {
        $idea->load('author', 'participants.user', 'comments.user');

        $hasJoined = false;
        if (Auth::check()) {
            $hasJoined = $idea->participants()->where('user_id', Auth::id())->exists();
        }

        return view('ideas.show', compact('idea', 'hasJoined'));
    }

    public function join(Request $request, ResearchIdea $idea)
    {
        $userId = Auth::id();

        $exists = IdeaParticipant::where('idea_id', $idea->id)->where('user_id', $userId)->exists();
        if ($exists) {
            return response()->json(['message' => 'Vous participez déjà à cette idée.'], 422);
        }

        IdeaParticipant::create([
            'idea_id' => $idea->id,
            'user_id' => $userId,
        ]);

        return response()->json(['message' => 'Vous avez rejoint cette idée.']);
    }

    public function leave(Request $request, ResearchIdea $idea)
    {
        IdeaParticipant::where('idea_id', $idea->id)->where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Vous avez quitté cette idée.']);
    }

    public function storeComment(Request $request, ResearchIdea $idea)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment = IdeaComment::create([
            'idea_id' => $idea->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json(['message' => 'Commentaire ajouté.', 'comment' => $comment]);
    }

    public function destroyComment(IdeaComment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }

    public function updateStatus(Request $request, ResearchIdea $idea)
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,completed'],
        ]);

        $idea->update(['status' => $request->status]);

        return response()->json(['message' => 'Statut mis à jour.', 'status' => $idea->status]);
    }

    public function convertToProject(ResearchIdea $idea)
    {
        $user = Auth::user();

        $project = Project::create([
            'title' => $idea->title,
            'description' => $idea->description,
            'user_id' => $idea->author_id,
            'created_by' => $user->id,
            'status' => 'active',
        ]);

        $idea->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Idée convertie en projet.',
            'project_id' => $project->id,
        ]);
    }
}
