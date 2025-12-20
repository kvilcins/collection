<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserMovieController;
use App\Http\Controllers\PageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/search', [MovieController::class, 'search'])->name('search.index');
Route::get('/api/search/suggestions', [MovieController::class, 'suggestions'])->name('search.suggestions');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserMovieController::class, 'index'])->name('dashboard');
    Route::post('/folders', [UserMovieController::class, 'storeFolder'])->name('folders.store');

    Route::post('/movies/{movie}/user', [UserMovieController::class, 'update'])->name('user-movies.update');
    Route::delete('/movies/{movie}/user', [UserMovieController::class, 'destroy'])->name('user-movies.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
