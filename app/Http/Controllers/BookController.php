<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $books = Book::all();
        $books = Book::withAvg('ratings', 'rating')
            ->get();
        // ->makeHidden('ratings_avg_rating')
        // ->each(function ($book) {
        //     $book->ratings_avg = (int) round($book->ratings_avg_rating);
        // });
        return response()->json($books, 200);
    }

    /**
     * Store a newly created Book in database
     * @param Request $request
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function store(BookRequest $request)
    {
        $validatedData = $request->all();
        $book = $this->bookService->createBook($validatedData);
        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $wordCount = $book->descriptionWordCount();
        return response()->json([
            'book' => $book,
            'word_count' => $wordCount,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Book $book
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validatedData = $request->all();
        $book = $this->bookService->updateBook($book, $validatedData);
        return response()->json($book, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {  //dependencies injection
        $book->delete();
        $book = $this->bookService->deleteBook($book);
        return response()->json(['message' => 'deleting book success'], 204);
    }
}
