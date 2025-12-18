<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'tmdb_id',
        'overview',
        'poster_path',
        'release_date',
        'status',
        'rating',
    ];
}
