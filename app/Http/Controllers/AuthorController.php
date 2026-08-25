<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with(['books:id,author_id,title,publication_year,total_copies'])
            ->orderByFullName()
            ->paginate(10)
            ->withQueryString();

        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(StoreAuthorRequest $request)
    {
        Author::create($request->validated());

        return redirect()->route('authors.index')
            ->with('success', 'Автор успешно создан.');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        return redirect()->route('authors.index')
            ->with('success', 'Автор успешно обновлён.');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->whereHas('bookIssues')->exists()) {
            return back()->withErrors([
                'delete' => 'Нельзя удалить автора, пока одна из его книг выдана читателю. Сначала верните все книги.',
            ]);
        }

        $author->delete();

        return redirect()->route('authors.index')
            ->with('success', 'Автор удалён.');
    }
}
