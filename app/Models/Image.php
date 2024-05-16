<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'path',
        'type',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
