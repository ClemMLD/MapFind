<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return match (request()->wantsJson()) {
            true => [
                'lat' => ['required', 'numeric'],
                'lng' => ['required', 'numeric'],
            ],
            default => [],
        };
    }
}
