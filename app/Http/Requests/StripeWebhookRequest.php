<?php

namespace App\Http\Requests;

use App\Rules\ValidStripeSecret;
use Illuminate\Foundation\Http\FormRequest;

class StripeWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data.object.lines.data.0.metadata.user_id' => ['required', 'string'],
            'data.object.lines.data.0.metadata.secret' => ['required', 'string', new ValidStripeSecret],
        ];
    }
}
