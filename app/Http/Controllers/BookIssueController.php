<?php

namespace App\Http\Controllers;

use App\Actions\IssueBookAction;
use App\Actions\ReturnBookAction;
use App\Http\Requests\FilterBookIssuesRequest;
use App\Http\Requests\StoreBookIssueRequest;
use App\Models\Book;
use App\Models\BookIssue;

class BookIssueController extends Controller
{
    public function index(FilterBookIssuesRequest $request)
    {
        $issues = BookIssue::query()
            ->withSearch()
            ->filter($request->validated())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $availableBooks = Book::query()
            ->with('author:id,first_name,last_name')
            ->withAvailableCopies()
            ->get()
            ->filter(fn (Book $book) => $book->available_copies > 0)
            ->pluck('title_with_author', 'id');

        $books = Book::orderBy('title')->get();

        return view('book_issues.index', compact('issues', 'availableBooks', 'books'));
    }

    public function store(StoreBookIssueRequest $request, IssueBookAction $action)
    {
        try {
            $action->execute(
                bookId: $request->integer('book_id'),
                readerName: $request->input('reader_name'),
                dueDate: \Illuminate\Support\Carbon::parse($request->input('due_date')),
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['book_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('book-issues.index')
            ->with('success', 'Книга выдана читателю.');
    }

    public function return(BookIssue $issue, ReturnBookAction $action)
    {
        $action->execute($issue);

        return redirect()->route('book-issues.index')
            ->with('success', 'Книга возвращена.');
    }
}
