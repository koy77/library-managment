<?php

namespace App\Http\Requests;

use App\Rules\Isbn;
use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $book = $this->route('book');
        $book->loadCount('bookIssues as active_issues_count');

        return [
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255', new NoHtml],
            'publication_year' => ['required', 'integer', 'min:1', 'max:' . date('Y')],
            'isbn' => ['required', 'string', new Isbn, new NoHtml, Rule::unique('books', 'isbn')->ignore($book->id)],
            'total_copies' => [
                'required',
                'integer',
                'min:0',
                'min:' . $book->active_issues_count,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'Книга с таким ISBN уже существует.',
            'total_copies.min' => 'Количество экземпляров не может быть меньше, чем выдано на руки.',
        ];
    }
}
