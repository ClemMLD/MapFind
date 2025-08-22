<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AccountUpdateRequest;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('account.index');
    }

    public function edit(): View
    {
        return view('account.edit');
    }

    public function avatar(): JsonResponse
    {
        if (request()->isMethod('delete')) {
            auth()->user()->update(['image' => null]);

            return response()->json('', 204);
        }

        $request = request();
        $request->validate([
            'avatar' => ['required', 'image', 'max:8096'],
        ]);
        $path = $request->file('avatar')->store('', 'public');
        auth()->user()->update(['image' => $path]);
        return response()->json('', 201);
    }

    public function update(AccountUpdateRequest $request): RedirectResponse
    {
        auth()->user()->fill($request->validated());

        if (auth()->user()->isDirty('email')) {
            auth()->user()->email_verified_at = null;
        }

        auth()->user()->save();

        return Redirect::route('account.edit', auth()->user())->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function listings(): View
    {
        return view('account.listings', [
            'listings' => auth()->user()->listings()->get(),
        ]);
    }

    public function upgrade(): View
    {
        return view('account.upgrade');
    }

    public function subscriptionPage(): RedirectResponse
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => env('STRIPE_PRICE_ID'),
                'quantity' => 1,
            ]],
            'metadata' => [
                'user_id' => auth()->id(),
            ],
            'subscription_data' => [
                'metadata' => [
                    'user_id' => auth()->id(),
                    'secret' => Hash::make(env('STRIPE_WEBHOOK_SECRET')),
                ],
            ],
            'success_url' => route('account.index') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('account.index'),
        ]);

        return redirect($session->url);
    }
}
