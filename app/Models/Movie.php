<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $appends = ['average_rating'];

    protected $fillable = [
        'title',
        'tmdb_id',
        'overview',
        'poster_path',
        'release_date',
        'vote_average'
    ];

    /**
     * Get the genres associated with the movie.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    /**
     * Get the tags associated with the movie.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'movie_tag');
    }

    /**
     * Get the users who have added this movie to their collection.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'personal_rating', 'folder_id')
            ->withTimestamps();
    }

    /**
     * Calculate the average rating based on local user ratings.
     */
    public function getAverageRatingAttribute()
    {
        $avg = $this->users()->avg('personal_rating');
        return $avg ? round($avg, 1) : 0;
    }
}
