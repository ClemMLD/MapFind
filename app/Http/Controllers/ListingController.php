<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\View\View;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use App\Http\Requests\ListingIndexRequest;
use App\Http\Requests\ListingBoostRequest;
use App\Http\Requests\ListingStoreRequest;
use App\Http\Requests\ListingUpdateRequest;

class ListingController extends Controller
{
    use ImageTrait;

    public function show(Listing $listing): JsonResponse|View
    {
        $isFavorite = auth()->user()->favorites()->where('listing_id', $listing->id)->exists();
        return view('listings.show', [
            'listing' => $listing->load(['category', 'user', 'images']),
            'isFavorite' => $isFavorite,
        ]);
    }

    public function index(ListingIndexRequest $request): JsonResponse|View
    {
        $userMaxListings = auth()->user()->listings()->count() >= auth()->user()->max_listings;

        if ($request->wantsJson()) {
            $listings = Listing::whereRaw(
                'ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) < 5000',
                [$request->get('lng'), $request->get('lat')]
            );

            if ($request->has('categories_id')) {
                $listings->whereHas('category', function ($query) use ($request) {
                    $query->whereIn('id', explode(',', $request->get('categories_id')));
                });
            }

            $listings = $listings->with(['category', 'user', 'images'])->get();

            return response()->json([
                'listings' => $listings,
                'userMaxListings' => $userMaxListings,
            ]);
        } else {
            return view('listings.index', ['userMaxListings' => $userMaxListings]);
        }
    }

    public function edit(Listing $listing): View
    {
        return view('listings.edit', [
            'listing' => $listing->load('images'),
            'categories' => $this->getCategories(),
            'currencies' => array_keys(config('currency')),
        ]);
    }

    private function getCategories(): array
    {
        return Category::all()->map(function ($category) {
            $categoryName = $category->name[App::getLocale()] ?? '';
            return [
                'id' => $category->id,
                'name' => $categoryName,
                'color' => $category->color,
            ];
        })->toArray();
    }

    public function store(ListingStoreRequest $request): JsonResponse
    {
        $images = [];
        foreach ($request->allFiles() as $key => $imageFile) {
            $imageName = $this->resizeAndStore($imageFile);
            $images[] = ['name' => $imageName];
        }

        $listingData = $request->validated();

        $listing = Listing::create($listingData);
        $listing->images()->createMany($images);

        return response()->json($listing, 201);
    }

    public function create(Request $request): View
    {
        return view('listings.create', [
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'categories' => $this->getCategories(),
            'currencies' => array_keys(config('currency')),
        ]);
    }

    public function boost(ListingBoostRequest $request): JsonResponse
    {
        $listingId = data_get($request->all(), 'id');
        Listing::findOrFail($listingId)
            ->update(['boosted' => true, 'boosted_at' => now()]);
        auth()->user()->decrement('boosts');

        return response()->json('', 202);
    }

    public function update(ListingUpdateRequest $request): JsonResponse|View
    {
        $id = $request->get('id');
        $listing = Listing::findOrFail($id);
        $images = [];

        foreach ($request->allFiles() as $key => $imageFile) {
            $imageName = $this->resizeAndStore($imageFile);
            $images[] = ['name' => $imageName];
        }

        if ($request->has('deleted_images')) {
            $ids = explode(',', $request->get('deleted_images'));
            $listing->images()->whereIn('id', $ids)->delete();
        }
        $listing->update($request->validated());
        $listing->images()->createMany($images);
        return response()->json($listing, 202);
    }

    public function destroy(Listing $listing): JsonResponse
    {
        if ($listing->user_id !== auth()->id()) {
            return response()->json('', 403);
        }
        $listing->delete();
        return response()->json('', 204);
    }
}
