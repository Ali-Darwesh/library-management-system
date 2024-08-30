<?php

namespace App\Services;

use App\Models\Book;
use APP\Repositories\BookRepositories as RepositoriesBookRepositories;


class BookService
{

    // public function getBookByAuthor()
    // {
    //     return $this->bookRepository->getByAuthor();
    // }
    public function createBook(array $data)
    {
        return Book::create($data);
    }
}
