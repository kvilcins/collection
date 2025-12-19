<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Folder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserMovieController extends Controller
{
    /**
     * Display the user's collection with folders.
     */
    public function index(Request $request)
    {
        return Inertia::render('Dashboard', [
            'movies' => $request->user()->movies()->get()->map(fn ($movie) => [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster_url' => $movie->poster_path ? "https://image.tmdb.org/t/p/w500{$movie->poster_path}" : null,
                'status' => $movie->pivot->status,
                'personal_rating' => $movie->pivot->personal_rating,
                'folder_id' => $movie->pivot->folder_id,
            ]),
            'folders' => $request->user()->folders()->get()
        ]);
    }

    /**
     * Create a new folder.
     */
    public function storeFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->user()->folders()->create(['name' => $request->name]);
        return back();
    }

    /**
     * Update movie data (rating, status, folder).
     */
    public function update(Request $request, Movie $movie)
    {
        $request->user()->movies()->syncWithoutDetaching([
            $movie->id => [
                'status' => $request->status ?? 'watchlist',
                'personal_rating' => $request->personal_rating,
                'folder_id' => $request->folder_id,
            ]
        ]);
        return back();
    }

    /**
     * Remove the movie from the user's collection.
     */
    public function destroy(Request $request, Movie $movie)
    {
        $request->user()->movies()->detach($movie->id);
        return back();
    }
}
