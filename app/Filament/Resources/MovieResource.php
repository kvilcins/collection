<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Pages;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';

    /**
     * Define the form schema for creating and editing records.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('search')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(function ($state, $set) {
                                if (!$state) return;

                                $response = Http::withToken(config('services.tmdb.token'))
                                    ->get("https://api.themoviedb.org/3/search/movie", ['query' => $state])
                                    ->json();

                                $data = $response['results'][0] ?? null;

                                if ($data) {
                                    $tmdbId = $data['id'];
                                    $set('tmdb_id', $tmdbId);
                                    $set('overview', $data['overview']);
                                    $set('release_date', $data['release_date']);
                                    $set('poster_path', $data['poster_path']);
                                    $set('vote_average', $data['vote_average'] ?? null);

                                    if (!empty($data['genre_ids'])) {
                                        $localGenreIds = Genre::whereIn('tmdb_genre_id', $data['genre_ids'])
                                            ->pluck('id')->toArray();
                                        $set('genres', $localGenreIds);
                                    }

                                    $keywordsData = Http::withToken(config('services.tmdb.token'))
                                        ->get("https://api.themoviedb.org/3/movie/{$tmdbId}/keywords")
                                        ->json();

                                    if (!empty($keywordsData['keywords'])) {
                                        $tagIds = [];
                                        foreach ($keywordsData['keywords'] as $keyword) {
                                            $tag = Tag::firstOrCreate(
                                                ['name' => $keyword['name']],
                                                ['slug' => Str::slug($keyword['name'])]
                                            );
                                            $tagIds[] = $tag->id;
                                        }
                                        $set('tags', $tagIds);
                                    }

                                    \Filament\Notifications\Notification::make()->title('Data & Keywords fetched!')->success()->send();
                                }
                            })
                    ),

                Forms\Components\TextInput::make('tmdb_id')->required()->readOnly(),
                Forms\Components\DatePicker::make('release_date'),
                Forms\Components\TextInput::make('vote_average')->label('IMDb/TMDB Rating')->numeric(),

                Forms\Components\Select::make('genres')
                    ->multiple()->relationship('genres', 'name')->preload(),

                Forms\Components\Select::make('tags')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                    ]),

                Forms\Components\Textarea::make('overview')->columnSpanFull(),
                Forms\Components\TextInput::make('poster_path'),
            ]);
    }

    /**
     * Define the table columns and actions for the list view.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('poster_path')
                    ->state(fn($record) => "https://image.tmdb.org/t/p/w200{$record->poster_path}")
                    ->size(80),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('vote_average')->label('IMDb Rating')->sortable(),
                Tables\Columns\TextColumn::make('genres.name')->badge()->color('success'),
                Tables\Columns\TextColumn::make('tags.name')->badge()->color('info')->limitList(3),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    /**
     * Register the pages used by this resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}
