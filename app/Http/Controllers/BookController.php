<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

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
        $author = $request->query('author');
        $books = Book::byAuthor($author)->get();
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
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {  //dependencies injection
        $book->delete();
        return response()->json(null, 204);
    }
}
