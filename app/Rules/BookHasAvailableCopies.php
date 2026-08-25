<?php

namespace App\Rules;

use App\Models\Book;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BookHasAvailableCopies implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $book = Book::withAvailableCopies()->find($value);

        if (!$book || $book->available_copies <= 0) {
            $fail('Нет доступных экземпляров этой книги.');
        }
    }
}
