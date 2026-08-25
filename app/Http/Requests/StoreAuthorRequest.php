<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', new NoHtml],
            'last_name' => ['required', 'string', 'max:255', new NoHtml],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Имя автора обязательно.',
            'last_name.required' => 'Фамилия автора обязательна.',
        ];
    }
}
