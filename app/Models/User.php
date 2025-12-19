<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class)
            ->withPivot('status', 'personal_rating', 'folder_id')
            ->withTimestamps();
    }
}
