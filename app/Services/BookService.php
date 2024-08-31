<?php

namespace App\Services;

use App\Models\Book;
use APP\Repositories\BookRepositories as RepositoriesBookRepositories;


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
}
