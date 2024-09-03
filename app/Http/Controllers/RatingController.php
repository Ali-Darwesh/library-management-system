<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }
        $ratings = Rating::all();
        return response()->json($ratings);
    }

    /**
     * store the specified resource in storage.
     * @param StoreRatingRequest $request
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function store(StoreRatingRequest $request)
    {

        $validatedData = $request->all();

        $rating = Rating::create($validatedData);

        return response()->json($rating, 201);
    }

    /**
     * display a specified data.
     * @param Rating $rating
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function show(Rating $rating)
    {
        $rating = Rating::where('id', $rating->id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($rating);
    }

    /**
     * store the specified resource in storage.
     * @param Request $request
     * @param Rating $rating
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function update(Request $request, Rating $rating)
    {
        $rating = Rating::where('id', $rating->id)->where('user_id', Auth::id())->firstOrFail();

        $validatedData = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating->update($validatedData);

        return response()->json($rating);
    }

    /**
     * Remove the specified resource from storage.
     * @param Rating $rating
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function destroy(Rating $rating)
    {
        $authenticatedUser  = Auth::user();
        // Ensure that there is an authenticated user
        if (!$authenticatedUser  || (!$authenticatedUser->is_admin && $rating->user_id !== $authenticatedUser->id)) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }

        $rating->delete();

        return response()->json(['message' => 'Rating deleted successfully'], 200);
    }
}
