<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;

class FilterBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255', new NoHtml],
            'author_id' => ['nullable', 'integer', 'exists:authors,id'],
            'isbn' => ['nullable', 'string', 'max:17', new NoHtml],
        ];
    }
}