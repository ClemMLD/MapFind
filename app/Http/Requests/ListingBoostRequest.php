<?php

namespace App\Http\Requests;

use App\Models\Listing;
use Illuminate\Foundation\Http\FormRequest;

class ListingBoostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $listingId = data_get($this->toArray(), 'id');
        $listing = Listing::findOrFail($listingId);
        return (auth()->user()->id === $listing->user->id) && auth()->user()->boosts > 0;
    }
}
