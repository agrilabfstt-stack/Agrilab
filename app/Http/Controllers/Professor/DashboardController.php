<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $professor = Auth::user();

        $students = User::where('professor_id', $professor->id)
            ->withCount('projects')
            ->get();

        $baseQuery = function ($q) use ($professor) {
            $q->where(function ($inner) use ($professor) {
                $inner->whereHas('user', fn($sq) => $sq->where('professor_id', $professor->id))
                      ->orWhere('created_by', $professor->id);
            });
        };

        $recentProjects = Project::where(function ($q) use ($professor) {
            $q->whereHas('user', fn($sq) => $sq->where('professor_id', $professor->id))
              ->orWhere('created_by', $professor->id);
        })->with('user', 'category')->latest()->take(5)->get();

        $totalProjects = Project::where(function ($q) use ($professor) {
            $q->whereHas('user', fn($sq) => $sq->where('professor_id', $professor->id))
              ->orWhere('created_by', $professor->id);
        })->count();

        $stats = [
            'students'  => $students->count(),
            'projects'  => $totalProjects,
            'active'    => Project::where(function ($q) use ($professor) {
                $q->whereHas('user', fn($sq) => $sq->where('professor_id', $professor->id))
                  ->orWhere('created_by', $professor->id);
            })->where('status', 'active')->count(),
            'completed' => Project::where(function ($q) use ($professor) {
                $q->whereHas('user', fn($sq) => $sq->where('professor_id', $professor->id))
                  ->orWhere('created_by', $professor->id);
            })->where('status', 'completed')->count(),
        ];

        return view('professor.dashboard', compact('students', 'recentProjects', 'stats'));
    }
}
