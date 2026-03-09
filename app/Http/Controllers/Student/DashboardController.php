<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $projects = Project::where('user_id', $student->id)
            ->with('category')
            ->latest()
            ->get();

        $stats = [
            'total' => $projects->count(),
            'active' => $projects->where('status', 'active')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
            'blocked' => $projects->where('status', 'blocked')->count(),
        ];

        return view('student.dashboard', compact('student', 'projects', 'stats'));
    }
}
