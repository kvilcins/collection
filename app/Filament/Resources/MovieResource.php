<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Pages;
use App\Models\Movie;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Movie Title')
                    ->required()
                    ->suffixAction(
                        Action::make('searchTmdb')
                            ->icon('heroicon-m-magnifying-glass')
                            ->tooltip('Search on TMDb')
                            ->action(function ($state, $set) {
                                if (!$state) return;

                                $response = Http::withToken(config('services.tmdb.token'))
                                    ->get("https://api.themoviedb.org/3/search/movie", [
                                        'query' => $state,
                                        'language' => 'en-US',
                                    ])->json();

                                $movie = $response['results'][0] ?? null;

                                if ($movie) {
                                    $set('overview', $movie['overview']);
                                    $set('release_date', $movie['release_date']);
                                    $set('tmdb_id', $movie['id']);
                                    $set('poster_path', $movie['poster_path']);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Movie found!')
                                        ->success()
                                        ->send();
                                } else {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Nothing found')
                                        ->danger()
                                        ->send();
                                }
                            })
                    ),

                TextInput::make('tmdb_id')
                    ->label('TMDb ID')
                    ->dehydrated()
                    ->required()
                    ->readOnly(),

                DatePicker::make('release_date')
                    ->label('Release Date'),

                Textarea::make('overview')
                    ->label('Overview')
                    ->columnSpanFull(),

                TextInput::make('poster_path')
                    ->label('Poster Path (API)')
                    ->helperText('Image preview will be implemented later'),

                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'watchlist' => 'Watchlist',
                        'watched' => 'Watched',
                    ])
                    ->default('watchlist')
                    ->required(),

                \Filament\Forms\Components\Select::make('rating')
                    ->options(array_combine(range(1, 10), range(1, 10)))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDb ID'),
                Tables\Columns\ImageColumn::make('poster_path')
                    ->label('Poster')
                    ->state(fn ($record) => $record->poster_path
                        ? "https://image.tmdb.org/t/p/w200{$record->poster_path}"
                        : null
                    )
                    ->size(100),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}
