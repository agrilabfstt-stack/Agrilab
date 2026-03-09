<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'professors' => User::where('role', 'professor')->count(),
            'projects' => Project::where('status', 'active')->count(),
            'categories' => Category::count(),
        ];

        $categories = Category::withCount('projects')->get();

        return view('welcome', compact('stats', 'categories'));
    }
}
