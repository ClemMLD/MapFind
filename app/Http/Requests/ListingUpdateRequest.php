<?php

namespace App\Http\Requests;

use App\Models\Listing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $listingId = data_get($this->toArray(), 'id');
        $listing = Listing::findOrFail($listingId);

        return auth()->id() === $listing->user->id;
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $files = $this->allFiles();
            $deletedImages = explode(',', data_get($this->toArray(), 'deleted_images', ''));
            $listing = Listing::findOrFail(data_get($this->toArray(), 'id'));
            $listingImagesCount = count($listing->images);

            $remainingImagesCount = $listingImagesCount - count($deletedImages) + count($files);

            if (auth()->user()->type !== 'premium' && auth()->user()->type !== 'partner') {
                if ($remainingImagesCount > 1) {
                    $validator->errors()->add('files', 'standard_images_limit');
                }
            } else {
                if ($remainingImagesCount > 10) {
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
                'id' => ['required', 'exists:listings,id'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'category_id' => ['nullable', 'exists:categories,id'],
                'price' => ['nullable', 'numeric'],
                'currency' => ['nullable', 'string', 'max:3'],
                'images' => ['array', 'nullable'],
                'images.*' => ['image', 'max:8096'],
                'address' => ['nullable', 'string'],
            ],
            default => []
        };
    }

    public function messages(): array
    {
        return [
            'id.required' => 'id_required',
            'id.exists' => 'id_invalid',
            'title.string' => 'title_string',
            'title.max' => 'title_max',
            'description.string' => 'description_string',
            'category_id.exists' => 'category_id_invalid',
            'price.numeric' => 'price_numeric',
            'capacity.numeric' => 'capacity_numeric',
            'image.image' => 'image_image',
            'image.max' => 'image_max',
            'address.string' => 'address_string',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'code' => $validator->errors()->first(),
        ], 422));
    }
}
