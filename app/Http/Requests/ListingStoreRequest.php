<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->listings()->count() < auth()->user()->max_listings;
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $files = $this->allFiles();

            if (auth()->user()->type !== 'premium' && auth()->user()->type !== 'partner') {
                if (count($files) > 1) {
                    $validator->errors()->add('files', 'standard_images_limit');
                }
            } else {
                if (count($files) > 10) {
                    $validator->errors()->add('files', 'max_images_limit');
                }
            }
        });
    }

    public function rules(): array
    {
        return match (request()->wantsJson())
        {
            true => [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'numeric'],
                'currency' => ['required', 'string', 'max:3'],
                'address' => ['required', 'string'],
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
                'user_id' => ['required', 'exists:users,id'],
            ],
            default => []
        };
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id()
        ]);
    }

    public function messages(): array
    {
        return [
            'title.required' => 'title_required',
            'title.string' => 'title_string',
            'title.max' => 'title_max',
            'description.required' => 'description_required',
            'description.string' => 'description_string',
            'category_id.required' => 'category_id_required',
            'category_id.exists' => 'category_id_invalid',
            'price.required' => 'price_required',
            'price.numeric' => 'price_numeric',
            'currency.required' => 'currency_required',
            'currency.string' => 'currency_string',
            'currency.max' => 'currency_max',
            'image.image' => 'image_image',
            'image.max' => 'image_max',
            'address.required' => 'address_required',
            'address.string' => 'address_string',
            'latitude.required' => 'latitude_required',
            'latitude.numeric' => 'latitude_numeric',
            'longitude.required' => 'longitude_required',
            'longitude.numeric' => 'longitude_numeric',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'code' => $validator->errors()->first(),
        ], 422));
    }

    protected function failedAuthorization(): void
    {
        if (request()->wantsJson()) {
            throw new HttpResponseException(response()->json([
                'code' => 'max_listings_limit',
            ], 403));
        } else {
            abort(Response::HTTP_FORBIDDEN);
        }
    }
}
