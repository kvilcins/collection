<?php

namespace App\Filament\Resources\MovieResource\Pages;

use App\Filament\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Tag;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ListMovies extends ListRecords
{
    protected static string $resource = MovieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importPopular')
                ->label('Import Popular')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $response = Http::withToken(config('services.tmdb.token'))
                        ->get("https://api.themoviedb.org/3/movie/popular")->json();

                    foreach ($response['results'] as $data) {
                        $movie = Movie::updateOrCreate(
                            ['tmdb_id' => $data['id']],
                            [
                                'title' => $data['title'],
                                'overview' => $data['overview'],
                                'poster_path' => $data['poster_path'],
                                'release_date' => $data['release_date'] ?? null,
                            ]
                        );

                        if (!empty($data['genre_ids'])) {
                            $localGenreIds = Genre::whereIn('tmdb_genre_id', $data['genre_ids'])->pluck('id')->toArray();
                            $movie->genres()->sync($localGenreIds);
                        }

                        $keywordsResponse = Http::withToken(config('services.tmdb.token'))
                            ->get("https://api.themoviedb.org/3/movie/{$data['id']}/keywords")
                            ->json();

                        if (!empty($keywordsResponse['keywords'])) {
                            $tagIds = [];
                            foreach ($keywordsResponse['keywords'] as $keyword) {
                                $tag = Tag::firstOrCreate(
                                    ['name' => $keyword['name']],
                                    ['slug' => Str::slug($keyword['name'])]
                                );
                                $tagIds[] = $tag->id;
                            }
                            $movie->tags()->sync($tagIds);
                        }
                    }

                    \Filament\Notifications\Notification::make()->title('Import completed with Genres and Tags!')->success()->send();
                }),
        ];
    }
}
