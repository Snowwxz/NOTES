<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CollabController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('login');
});

Route::get('/setelahlLogin', function () {
    return view('setelahlLogin');
});


// Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Landing
Route::get('/', [NotesController::class, 'landing'])->name('landing');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes (butuh login)
Route::middleware('auth')->group(function () {
    // Notes
    Route::get('notes', [NotesController::class,'index'])->name('notes.index');
    Route::get('notes/public', [NotesController::class,'public'])->name('notes.public');
    Route::get('/notes/create', [NotesController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}', [NotesController::class, 'show'])->name('notes.show');
    Route::get('/notes/{id}/edit', [NotesController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [NotesController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [NotesController::class, 'destroy'])->name('notes.destroy');

    // Likes
    Route::post('/notes/{note}/like', [LikeController::class, 'toggle'])->name('notes.like');
    Route::get('/notes/{note}/like/check', [LikeController::class, 'check'])->name('notes.like.check');

    // Collaboration
    Route::post('/collab/add', [CollabController::class, 'addCollaborator'])->name('collab.add');
    Route::post('/collab/remove', [CollabController::class, 'removeCollaborator'])->name('collab.remove');
    Route::get('/collab/list', [CollabController::class, 'getCollaborators'])->name('collab.list');
    Route::get('/collab/available-users', [CollabController::class, 'getAvailableUsers'])->name('collab.available-users');

    // Profile
    Route::get('/profile', [NotesController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [NotesController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [NotesController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes default Laravel Breeze/Fortify
require __DIR__.'/auth.php';
