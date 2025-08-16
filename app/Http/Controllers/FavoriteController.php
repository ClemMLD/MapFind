<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        return view('account.favorites', [
            'favorites' => auth()->user()->favorites->load('listing'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $listingId = $request->input('listing_id');

        if (auth()->user()->favorites()->where('listing_id', $listingId)->exists()) {
            return response()->json(409);
        }

        auth()->user()->favorites()->create([
            'listing_id' => $listingId,
        ]);

        return response()->json('', 201);
    }

    public function destroy(string $id): JsonResponse
    {
        auth()->user()->favorites()->where('listing_id', $id)->delete();

        return response()->json('', 204);
    }
}
