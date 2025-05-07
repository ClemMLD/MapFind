<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WelcomeController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('listings.index');
        }
        return view('welcome');
    }
}
