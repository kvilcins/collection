<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
