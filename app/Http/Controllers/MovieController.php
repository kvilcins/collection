<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class MovieController extends Controller
{
    /**
     * Display the main movie catalog.
     */
    public function index()
    {
        return Inertia::render('Welcome', [
            'movies' => Movie::with('genres')->latest()->get()->map(function($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'overview' => $movie->overview,
                    'poster_url' => $movie->poster_path
                        ? "https://image.tmdb.org/t/p/w500{$movie->poster_path}"
                        : null,
                    'rating' => $movie->average_rating,
                    'global_rating' => $movie->vote_average,
                    'genres' => $movie->genres->pluck('name'),
                ];
            }),
            'genres' => Genre::orderBy('name')->get(['id', 'name'])
        ]);
    }

    /**
     * Display search results page.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $movies = Movie::with('genres')
            ->where('title', 'like', "%{$query}%")
            ->orWhereHas('genres', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'poster_url' => $movie->poster_path ? "https://image.tmdb.org/t/p/w500{$movie->poster_path}" : null,
                    'rating' => $movie->average_rating,
                    'global_rating' => $movie->vote_average,
                    'genres' => $movie->genres->pluck('name'),
                ];
            });

        return Inertia::render('Search/Results', [
            'movies' => $movies,
            'query' => $query
        ]);
    }

    /**
     * API for autocomplete suggestions.
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $movies = Movie::where('title', 'like', "%{$query}%")
            ->take(5)
            ->get(['id', 'title', 'poster_path', 'release_date', 'vote_average']);

        return response()->json($movies->map(function ($movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'year' => $movie->release_date ? Carbon::parse($movie->release_date)->year : '',
                'rating' => $movie->vote_average,
                'poster_url' => $movie->poster_path ? "https://image.tmdb.org/t/p/w92{$movie->poster_path}" : null,
            ];
        }));
    }

    /**
     * Display a single movie page.
     */
    public function show(Movie $movie)
    {
        $movie->load(['genres', 'tags']);

        $userMovie = null;

        if (Auth::check()) {
            $userMovie = Auth::user()->movies()->where('movie_id', $movie->id)->first();
        }

        return Inertia::render('Movie/Show', [
            'movie' => [
                'id' => $movie->id,
                'title' => $movie->title,
                'overview' => $movie->overview,
                'release_date' => $movie->release_date,
                'poster_url' => $movie->poster_path
                    ? "https://image.tmdb.org/t/p/original{$movie->poster_path}"
                    : null,
                'rating' => $movie->average_rating,
                'global_rating' => $movie->vote_average,
                'genres' => $movie->genres->pluck('name'),
                'tags' => $movie->tags->pluck('name'),
            ],
            'folders' => Auth::check() ? Auth::user()->folders : [],
            'user_movie' => $userMovie,
        ]);
    }
}
