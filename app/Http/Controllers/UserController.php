<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    /**
     * display a specified data.
     * @param $id
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function show($id)
    {
        $user = Auth::user();
        // Ensure that there is an authenticated user
        if (!$user || (!$user->is_admin && $id !== $user->id)) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }
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
    public function update(UpdateUserRequest $request, User $user)
    {

        $validatedData = $request->all();
        $user->update($validatedData);

        return response()->json($user, 201);
    }
    /**
     * delete user data in database
     * @param User $user
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $authenticatedUser  = Auth::user();
        // Ensure that there is an authenticated user
        if (!$authenticatedUser  || (!$authenticatedUser->is_admin && $user->id !== $authenticatedUser->id)) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }
        $user->delete();
        return response()->json($user, 200);
    }
}
