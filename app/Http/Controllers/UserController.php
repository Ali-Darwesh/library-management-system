<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $authController = new AuthController();
        return $authController->register($request);

        return response()->json($user, 201);
    }

    /**
     * Update the specified resource (user) in storage.
     * @param Request $request , User $user
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            // return response()->json($request->all(), 200);
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',  // Validate name if provided
                'email' => 'sometimes|email|max:255|unique:users,email',  // Validate email if provided and ensure it's unique
                'password' => 'sometimes|string|min:6',  // Validate password if provided
            ]);


            $user = $user->update($validatedData);

            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update rating', 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * delete user data in database
     * @param Movie $movie
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user', 'message' => $e->getMessage()], 500);
        }
    }
}
