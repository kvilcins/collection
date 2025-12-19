<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/genre/movie/list?language=en-US")
            ->json();

        if (isset($response['genres'])) {
            foreach ($response['genres'] as $genre) {
                Genre::updateOrCreate(
                    ['tmdb_genre_id' => $genre['id']],
                    ['name' => $genre['name']]
                );
            }
        }
    }
}
