<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BlockedUserController extends Controller
{
    public function index(): JsonResponse|View
    {
        if (request()->wantsJson()) {
            $blockedUsers = BlockedUser::where('user_id', auth()->user()->id)->get();
            $users = [];
            foreach ($blockedUsers as $blockedUser) {
                $users[] = User::where('id', $blockedUser->blocked_user_id)->first();
            }
            return response()->json($users);
        } else {
            $users = auth()->user()->blockedUsers->pluck('blockedUser')->map(function ($user) {
                return $user->only(['id', 'name', 'nickname']);
            });

            return view('blocked-users.index', ['users' => $users]);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $id = $request->get('user_id');
        if (auth()->user()->id === $id) {
            return response()->json('', 422);
        }

        if (
            BlockedUser::where('user_id', auth()->user()->id)
                ->where('blocked_user_id', $id)
                ->first()
        ) {
            return response()->json('', 403);
        }

        BlockedUser::create([
            'user_id' => auth()->user()->id,
            'blocked_user_id' => $id,
        ]);

        return response()->json('', 201);
    }

    public function destroy(User $blockedUser): JsonResponse
    {
        if ($blockedUser->id === auth()->user()->id) {
            return response()->json('', 422);
        }

        BlockedUser::where('user_id', auth()->user()->id)
            ->where('blocked_user_id', $blockedUser->id)
            ->delete();

        return response()->json('', 204);
    }
}
