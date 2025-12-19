<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $appends = ['average_rating'];

    protected $fillable = ['title', 'tmdb_id', 'overview', 'poster_path', 'release_date'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'movie_tag');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'personal_rating', 'folder_id')
            ->withTimestamps();
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->users()->avg('personal_rating');
        return $avg ? round($avg, 1) : 0;
    }
}
