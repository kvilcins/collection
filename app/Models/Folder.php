<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'user_id'];

    /**
     * Get the user that owns the folder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movies contained in this folder.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_user');
    }
}
