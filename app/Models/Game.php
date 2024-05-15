<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Get the user that owns the game.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'game_user');
    }
}