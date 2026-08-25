<?php

namespace App\Http\Requests;

use App\Models\BookIssue;
use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterBookIssuesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reader_name' => ['nullable', 'string', 'max:255', new NoHtml],
            'book_id' => ['nullable', 'integer', 'exists:books,id'],
            'issued_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in([BookIssue::STATUS_ACTIVE, BookIssue::STATUS_OVERDUE])],
        ];
    }
}