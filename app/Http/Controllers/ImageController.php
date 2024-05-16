<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Upload an image for a game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request, $gameId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:cover,gallery',
        ]);

        $game = Game::findOrFail($gameId);

        $path = $request->file('image')->store('public/' . $request->type . 's');

        $image = new Image([
            'path' => Storage::url($path),
            'type' => $request->type,
        ]);

        $game->images()->save($image);

        return response()->json(['image' => $image], 201);
    }

    /**
     * Update the cover image for a game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoverImage(Request $request, $gameId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $game = Game::findOrFail($gameId);

        // Obtener la imagen de portada actual
        $coverImage = $game->coverImage;

        if ($coverImage) {
            // Eliminar la imagen de portada actual del almacenamiento
            Storage::delete(str_replace('/storage/', 'public/', $coverImage->path));

            // Eliminar el registro de la base de datos
            $coverImage->delete();
        }

        // Subir la nueva imagen de portada
        $path = $request->file('image')->store('public/covers');

        // Crear el nuevo registro de la imagen
        $newImage = new Image([
            'path' => Storage::url($path),
            'type' => 'cover',
        ]);

        $game->images()->save($newImage);

        return response()->json(['image' => $newImage], 200);
    }

    /**
     * Remove the specified image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Eliminar la imagen del almacenamiento
        Storage::delete(str_replace('/storage/', 'public/', $image->path));

        // Eliminar el registro de la base de datos
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully.'], 200);
    }
}
