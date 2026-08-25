<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterBooksRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;

class BookController extends Controller
{
    public function index(FilterBooksRequest $request)
    {
        $books = Book::query()
            ->with('author:id,first_name,last_name')
            ->withAvailableCopies()
            ->filter($request->validated())
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        $authors = Author::orderByFullName()->get();

        return view('books.index', compact('books', 'authors'));
    }

    public function create()
    {
        $authors = Author::orderByFullName()->get();

        return view('books.create', compact('authors'));
    }

    public function store(StoreBookRequest $request)
    {
        Book::create($request->validated());

        return redirect()->route('books.index')
            ->with('success', 'Книга успешно добавлена.');
    }

    public function edit(Book $book)
    {
        $book->loadCount('bookIssues as active_issues_count');

        $authors = Author::orderByFullName()->get();

        return view('books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return redirect()->route('books.index')
            ->with('success', 'Книга успешно обновлена.');
    }

    public function destroy(Book $book)
    {
        if ($book->bookIssues()->exists()) {
            return back()->withErrors([
                'delete' => 'Нельзя удалить книгу, пока она выдана читателю. Сначала верните все экземпляры.',
            ]);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Книга удалена.');
    }
}
