<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Constracor to inject Book Service
     * @param BookService $bookService
     */
    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['author', 'is_available', 'category']);
        $sortBy = $request->query('sort_by');
        $sortOrder = $request->query('sort_order', 'asc');
        $books = Book::filter($filters)
            ->withAvg('ratings', 'rating')
            ->sort($sortBy, $sortOrder)
            ->get()
            ->groupBy('category');

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
     * Store a newly created Book in database
     * @param Request $request
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function show(Book $book)
    {
        $book = book::with('ratings')->find($book->id);

        $rating = $book->ratings()->avg('rating');

        return response()->json([
            'data' => [
                'book' => $book,
                'average_rating' => $rating,
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\HTTP\JsonResponse

     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validatedData = $request->all();
        $this->bookService->updateBook($book, $validatedData);
        return response()->json($book, 201);
    }

    /**
     * Remove the specified resource from storage.
     * @param Book $book
     * @return \Illuminate\HTTP\JsonResponse
     */
    public function destroy(Book $book)
    {
        $user = Auth::user();
        // Ensure that there is an authenticated user
        if (!$user || !$user->is_admin) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }
        $book->delete();
        $book = $this->bookService->deleteBook($book);
        return response()->json(['message' => 'deleting book success'], 200);
    }
}
