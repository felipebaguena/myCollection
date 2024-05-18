<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new user instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nickname' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create the user with role_code = 'USER' by default
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_code' => 'USER', // Assign role_code = 'USER' by default
        ]);

        return response()->json(['user' => $user], 201);
    }

    /**
     * Add a game to the user's library.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGameToLibrary(Request $request, $userId)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Find the user
        $user = User::findOrFail($userId);

        // Check if the game is already in the user's library
        if ($user->games()->where('game_id', $request->game_id)->exists()) {
            return response()->json(['message' => 'Game is already in the library.'], 400);
        }

        // Find the game
        $game = Game::findOrFail($request->game_id);

        // Attach the game to the user's library
        $user->games()->attach($game);

        return response()->json(['message' => 'Game added to library successfully.'], 200);
    }

    /**
     * Get the user's library with all games.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLibrary($userId)
    {
        // Find the user with games
        $user = User::with('games')->findOrFail($userId);

        return response()->json(['library' => $user->games], 200);
    }

    /**
     * Remove a game from the user's library.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @param  int  $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeGameFromLibrary(Request $request, $userId, $gameId)
    {
        // Find the user
        $user = User::findOrFail($userId);

        // Check if the game is in the user's library
        if (!$user->games()->where('game_id', $gameId)->exists()) {
            return response()->json(['message' => 'Game not found in the library.'], 404);
        }

        // Detach the game from the user's library
        $user->games()->detach($gameId);

        return response()->json(['message' => 'Game removed from library successfully.'], 200);
    }

    /**
     * Update the user's nickname and password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $userId)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'nickname' => 'sometimes|required|string|max:255|unique:users,nickname,' . $userId,
            'current_password' => 'required_with:password|string',
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Find the user
        $user = User::findOrFail($userId);

        // Check if current password matches
        if ($request->filled('current_password') && !Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
        }

        // Update the user's nickname and/or password
        if ($request->filled('nickname')) {
            $user->nickname = $request->nickname;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['user' => $user], 200);
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    /**
     * Delete a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($userId)
    {
        // Find the user
        $user = User::findOrFail($userId);

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
