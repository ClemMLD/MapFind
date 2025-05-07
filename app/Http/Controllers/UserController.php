<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user): RedirectResponse|View
    {
        if ($user->id === auth()->user()->id) {
            return redirect()->route('account.show');
        } else if (BlockedUser::where('user_id', $user->id)->where('blocked_user_id', auth()->user()->id)->first()) {
            return view('users.show', ['user' => $user, 'incomingBlocked' => true]);
        } else if (BlockedUser::where('user_id', auth()->user()->id)->where('blocked_user_id', $user->id)->first()) {
            return view('users.show', ['user' => $user, 'outgoingBlocked' => true]);
        }

        return view('users.show', ['user' => $user]);
    }
}
