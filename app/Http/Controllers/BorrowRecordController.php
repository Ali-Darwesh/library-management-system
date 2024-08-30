<?php

namespace App\Http\Controllers;

use App\Models\Borrow_record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $borrowedAt = Carbon::now(); // or use $request->borrowed_at if provided
        $dueDate = $borrowedAt->copy()->addDays(14);

        $borrowRecord = Borrow_record::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => null, // Set this later when the book is returned
        ]);

        return response()->json(['message' => 'Borrow record created successfully!', 'borrowRecord' => $borrowRecord], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow_record $borrow_record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrow_record $borrow_record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow_record $borrow_record)
    {
        //
    }
}
