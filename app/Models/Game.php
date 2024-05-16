<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'publisher',
        'launch_date',
        'description',
        'genre',
        'platform',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'game_user');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function coverImage()
    {
        return $this->hasOne(Image::class)->where('type', 'cover');
    }

    public function galleryImages()
    {
        return $this->hasMany(Image::class)->where('type', 'gallery');
    }

    // Sobrescribir el método delete para eliminar imágenes del almacenamiento
    public function delete()
    {
        // Eliminar las imágenes asociadas del almacenamiento
        foreach ($this->images as $image) {
            Storage::delete(str_replace('/storage/', 'public/', $image->path));
        }

        // Eliminar las imágenes de la base de datos
        $this->images()->delete();

        // Llamar al método delete original
        parent::delete();
    }
}
