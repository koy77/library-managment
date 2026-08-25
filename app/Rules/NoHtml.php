<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoHtml implements ValidationRule
{
    /**
     * Запрещает HTML/JavaScript-теги во вводимых данных (защита от XSS).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/<[^>]*>|<\/[^>]*>/', (string) $value)) {
            $fail('Поле :attribute не может содержать HTML/JavaScript-теги.');
        }
    }
}