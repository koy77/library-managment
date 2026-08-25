<?php

namespace App\Http\Requests;

use App\Rules\Isbn;
use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255', new NoHtml],
            'publication_year' => ['required', 'integer', 'min:1', 'max:' . date('Y')],
            'isbn' => ['required', 'string', new Isbn, new NoHtml, Rule::unique('books', 'isbn')],
            'total_copies' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'author_id.required' => 'Выберите автора.',
            'title.required' => 'Название книги обязательно.',
            'publication_year.max' => 'Год издания не может быть в будущем.',
            'isbn.unique' => 'Книга с таким ISBN уже существует.',
            'total_copies.min' => 'Количество экземпляров не может быть отрицательным.',
        ];
    }
}
