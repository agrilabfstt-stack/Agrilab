<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'professors' => User::where('role', 'professor')->count(),
            'students' => User::where('role', 'student')->count(),
            'projects' => Project::count(),
            'categories' => Category::count(),
        ];

        $recentUsers = User::whereIn('role', ['professor', 'student'])
            ->latest()
            ->take(5)
            ->get();

        $recentProjects = Project::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProjects'));
    }
}
