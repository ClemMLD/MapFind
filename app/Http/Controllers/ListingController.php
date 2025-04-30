<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ListingController extends Controller
{
    public function index(): View
    {
        return view('listings.index');
    }
}
