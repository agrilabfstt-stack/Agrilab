<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Professor;
use App\Http\Controllers\ResearchIdeaController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/migrate', function () {

    Artisan::call('migrate', ['--force' => true]);

    return response()->json([
        'success' => true,
        'message' => 'Migrations executed successfully',
        'output' => Artisan::output(),
    ]);
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Showcase (public)
Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
Route::get('/showcase/{project}', [ShowcaseController::class, 'show'])->name('showcase.show');

// Research Ideas (public read, auth for actions)
Route::get('/ideas', [ResearchIdeaController::class, 'index'])->name('ideas.index');
Route::get('/ideas/create', [ResearchIdeaController::class, 'create'])->name('ideas.create')->middleware('auth');
Route::get('/ideas/{idea}', [ResearchIdeaController::class, 'show'])->name('ideas.show');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', Admin\UserController::class)->except(['show']);
    Route::get('categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Professor routes
Route::prefix('professor')->name('professor.')->middleware(['auth', 'role:professor'])->group(function () {
    Route::get('/', [Professor\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', Professor\ProjectController::class);
    Route::patch('projects/{project}/status', [Professor\ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    Route::post('projects/{project}/data', [Professor\ProjectController::class, 'storeData'])->name('projects.data.store');
    Route::put('projects/{project}/data/{data}', [Professor\ProjectController::class, 'updateData'])->name('projects.data.update');
    Route::delete('projects/{project}/data/{data}', [Professor\ProjectController::class, 'destroyData'])->name('projects.data.destroy');
    Route::post('projects/{project}/attachments', [Professor\ProjectController::class, 'storeAttachment'])->name('projects.attachments.store');
    Route::delete('projects/{project}/attachments/{attachment}', [Professor\ProjectController::class, 'destroyAttachment'])->name('projects.attachments.destroy');
});

// Student routes
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', Student\ProjectController::class);
    Route::post('projects/{project}/data', [Student\ProjectController::class, 'storeData'])->name('projects.data.store');
    Route::put('projects/{project}/data/{data}', [Student\ProjectController::class, 'updateData'])->name('projects.data.update');
    Route::delete('projects/{project}/data/{data}', [Student\ProjectController::class, 'destroyData'])->name('projects.data.destroy');
    Route::delete('projects/{project}/data/{data}/files/{file}', [Student\ProjectController::class, 'destroyDataFile'])->name('projects.data.files.destroy');
    Route::post('projects/{project}/attachments', [Student\ProjectController::class, 'storeAttachment'])->name('projects.attachments.store');
    Route::delete('projects/{project}/attachments/{attachment}', [Student\ProjectController::class, 'destroyAttachment'])->name('projects.attachments.destroy');
});

// Comments (shared – accessible to authenticated users with correct role logic enforced in controller)
Route::middleware('auth')->group(function () {
    Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Research Ideas (authenticated actions)
    Route::post('/ideas', [ResearchIdeaController::class, 'store'])->name('ideas.store');
    Route::post('/ideas/{idea}/join', [ResearchIdeaController::class, 'join'])->name('ideas.join');
    Route::delete('/ideas/{idea}/leave', [ResearchIdeaController::class, 'leave'])->name('ideas.leave');
    Route::post('/ideas/{idea}/comments', [ResearchIdeaController::class, 'storeComment'])->name('ideas.comments.store');
    Route::delete('/idea-comments/{comment}', [ResearchIdeaController::class, 'destroyComment'])->name('ideas.comments.destroy');
});

// Showcase toggle & Idea management (professor/admin)
Route::middleware(['auth', 'role:professor,admin'])->group(function () {
    Route::patch('/projects/{project}/showcase', [ShowcaseController::class, 'toggleShowcase'])->name('projects.toggle-showcase');
    Route::patch('/ideas/{idea}/status', [ResearchIdeaController::class, 'updateStatus'])->name('ideas.update-status');
    Route::post('/ideas/{idea}/convert', [ResearchIdeaController::class, 'convertToProject'])->name('ideas.convert');
});
