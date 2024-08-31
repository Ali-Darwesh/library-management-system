<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRecordRequest;
use App\Http\Requests\UpdateBorrowRecordRequest;
use App\Models\Book;
use App\Models\Borrow_record;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class BorrowRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrow_records = Auth::user()->borrow_records;
        return response()->json($borrow_records);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowRecordRequest $request)
    {

        // Check if the book is available
        $book = Book::findOrFail($request->book_id);

        if (!$book->is_available) {
            return response()->json([
                'message' => 'This book is currently unavailable.',
            ], 400);
        }
        $validatedData = $request->all();
        $borrowRecord = Borrow_record::create($validatedData);
        $book->update(['is_available' => false]);
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
    public function update(UpdateBorrowRecordRequest $request, Borrow_record $borrow_record)
    {
        $validatedData = $request;
        $check = Borrow_record::checkIfReturned($validatedData, $borrow_record);
        $book = Book::findOrFail($borrow_record->book_id);
        if ($check['due_date'] == null) {
            $borrow_record->update(['returned_at' => $check['returned_at']]);
            $book->update(['is_available' => true]);
        } else {
            $borrow_record->update(['due_date' => $check['due_date']]);
        }
        return response()->json(['message' => $check['message'], 'borrow_record' => $borrow_record], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow_record $borrow_record)
    {
        $borrow_record->delete();
    }
}
