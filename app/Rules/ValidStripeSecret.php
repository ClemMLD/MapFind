<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStripeSecret implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Hash::check(env('STRIPE_WEBHOOK_SECRET'), $value)) {
            $fail($this->message());
        }
    }

    private function message(): string
    {
        return 'The provided secret is invalid.';
    }
}