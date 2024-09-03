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
        // $borrow_records = Auth::user()->borrow_records;
        $borrow_records = Borrow_record::all();
        return response()->json($borrow_records);
    }

    /**
     * store the specified resource in storage.
     * @param StoreBorrowRecordRequest $request 
     * @return \Illuminate\HTTP\JsonResponse

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
     * display a specified data.
     * @param Borrow_record $borrow_record
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function show(Borrow_record $borrow_record)
    {
        return response()->json([
            '$borrow_record' => $$borrow_record,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateBorrowRecordRequest $request 
     * @param Borrow_record $borrow_record
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function update(UpdateBorrowRecordRequest $request, Borrow_record $borrow_record)
    {
        $validatedData = $request;
        $check = Borrow_record::checkIfReturned($validatedData, $borrow_record);
        $book = Book::findOrFail($borrow_record->book_id);
        if ($validatedData->returned_at !== null) {
            $book->update(['is_available' => true]);
        }
        return response()->json(['message' => $validatedData->message, 'borrow_record' => $borrow_record], 201);
    }

    /**
     * Remove the specified resource from storage.
     * @param Borrow_record $borrow_record
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function destroy(Borrow_record $borrow_record)
    {
        $user = Auth::user();
        // Ensure that there is an authenticated user
        if (!$user || (!$user->is_admin && $borrow_record->user_id !== $user->id)) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }
        $borrow_record->delete();
        $book = Book::findOrFail($borrow_record->book_id);

        $book->update(['is_available' => true]);

        return response()->json(['message' => 'borrow_record deleted successfully'], 200);
    }
}
