<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($tag) => $tag->slug = Str::slug($tag->name));
    }

    /**
     * Get the movies associated with the tag.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
