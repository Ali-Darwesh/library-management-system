<?php

namespace App\Services;

use App\Models\Book;
use APP\Repositories\BookRepositories as RepositoriesBookRepositories;
use Illuminate\Database\Eloquent\Builder;

class BookService
{


    public function createBook(array $data)
    {
        return Book::create($data);
    }
    public function updateBook(Book $book, array $data)
    {
        return $book->update($data);
    }
    public function deleteBook(Book $book)
    {
        return $book->delete();
    }
    //Scope to filter data on director or genre

}
