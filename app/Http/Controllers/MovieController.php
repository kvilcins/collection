<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'movies' => Movie::latest()->get()->map(function($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'overview' => $movie->overview,
                    'release_date' => $movie->release_date,
                    'poster_url' => $movie->poster_path
                        ? "https://image.tmdb.org/t/p/w500{$movie->poster_path}"
                        : null,
                    'rating' => $movie->rating,
                    'status' => $movie->status,
                ];
            })
        ]);
    }
}
