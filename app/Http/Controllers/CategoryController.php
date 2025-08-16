<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::all()->map(function ($category) use ($request) {
            try {
                $categoryName = $category->name[$request->get('language', 'en')];
            } catch (Exception) {
                abort(404);
            }
            return [
                'id' => $category->id,
                'name' => $categoryName,
                'color' => $category->color,
            ];
        })->toArray();
        return response()->json($categories);
    }
}
