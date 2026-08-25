<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\BookIssue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IssueBookAction
{
    public function execute(int $bookId, string $readerName, Carbon $dueDate): BookIssue
    {
        return DB::transaction(function () use ($bookId, $readerName, $dueDate) {
            // Атомарная блокировка строки для исключения race condition
            $book = Book::withAvailableCopies()
                ->lockForUpdate()
                ->findOrFail($bookId);

            if ($book->available_copies <= 0) {
                throw new \RuntimeException('Нет доступных экземпляров этой книги.');
            }

            return BookIssue::create([
                'book_id' => $book->id,
                'reader_name' => $readerName,
                'issued_at' => now(),
                'due_date' => $dueDate,
            ]);
        });
    }
}
