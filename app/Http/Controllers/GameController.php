<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Store a newly created game in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'launch_date' => 'required|date',
            'description' => 'required|string',
            'genre' => 'required|string|max:255',
            'platform' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create the game
        $game = Game::create([
            'title' => $request->title,
            'publisher' => $request->publisher,
            'launch_date' => $request->launch_date,
            'description' => $request->description,
            'genre' => $request->genre,
            'platform' => $request->platform,
        ]);

        return response()->json(['game' => $game], 201);
    }

    /**
     * Update the specified game in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'publisher' => 'sometimes|required|string|max:255',
            'launch_date' => 'sometimes|required|date',
            'description' => 'sometimes|required|string',
            'genre' => 'sometimes|required|string|max:255',
            'platform' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Find the game and update its data
        $game = Game::findOrFail($id);

        $game->update($request->all());

        return response()->json(['game' => $game], 200);
    }

    /**
     * Remove the specified game from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $game = Game::findOrFail($id);

        $game->delete();

        return response()->json(['message' => 'Game deleted successfully.'], 200);
    }

    /**
     * Get all games with optional filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllGames(Request $request)
    {
        $query = Game::query();

        // Apply filters only if they are present
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('publisher')) {
            $query->where('publisher', 'like', '%' . $request->publisher . '%');
        }

        if ($request->filled('launch_date')) {
            $query->whereDate('launch_date', $request->launch_date);
        }

        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }

        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        $games = $query->get();

        return response()->json(['games' => $games], 200);
    }
}
