<?php

namespace App\Http\Requests;

use App\Rules\BookHasAvailableCopies;
use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => [
                'required',
                'integer',
                'exists:books,id',
                new BookHasAvailableCopies,
            ],
            'reader_name' => ['required', 'string', 'max:255', new NoHtml],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Выберите книгу.',
            'reader_name.required' => 'Введите имя читателя.',
            'due_date.required' => 'Укажите дату возврата.',
            'due_date.after_or_equal' => 'Дата возврата не может быть раньше сегодняшнего дня.',
        ];
    }
}
