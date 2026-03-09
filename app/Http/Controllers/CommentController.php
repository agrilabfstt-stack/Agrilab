<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $user = Auth::user();

        // Students can only comment on their own projects
        if ($user->isStudent() && $project->user_id !== $user->id) {
            abort(403);
        }

        // Professors can only comment on their students' projects
        if ($user->isProfessor()) {
            $studentIds = $user->students()->pluck('id');
            if (! $studentIds->contains($project->user_id) && $project->created_by !== $user->id) {
                abort(403);
            }
        }

        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment = $project->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['comment' => $comment->load('user')], 201);
        }

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();

        if ($comment->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Supprimé']);
        }

        return back()->with('success', 'Commentaire supprimé.');
    }
}
