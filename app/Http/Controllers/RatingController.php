<?php

namespace App\Http\Controllers;

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
        $ratings = Auth::user()->ratings;
        return response()->json($ratings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating = Rating::create([
            'user_id' => Auth::id(),
            'book_id' => $request['book_id'],
            'rating' => $request['rating'],
            'review' => $request['review'],
        ]);

        return response()->json($rating, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        $rating = Rating::where('id', $rating->id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($rating);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        echo Auth::id();
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
     */
    public function destroy(Rating $rating)
    {
        $rating = Rating::where('id', $rating->id)->where('user_id', Auth::id())->firstOrFail();
        $rating->delete();

        return response()->json(['message' => 'Rating deleted successfully']);
    }
}
