<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isbn implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(978|979)-\d{1,5}-\d{1,7}-\d{1,7}-\d$/', (string) $value)) {
            $fail('Неверный формат ISBN.');
        }
    }
}