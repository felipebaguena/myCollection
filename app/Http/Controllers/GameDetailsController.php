<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameDetailsController extends Controller
{
    /**
     * Display the specified game with its images.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $game = Game::with('coverImage', 'galleryImages')->findOrFail($id);
        return response()->json($game);
    }
}
