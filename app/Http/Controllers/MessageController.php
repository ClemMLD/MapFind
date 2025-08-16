<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\View\View;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $messages = Message::select(DB::raw('CASE WHEN sender_id = ' . $userId . ' THEN receiver_id ELSE sender_id END AS user_id'))
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->whereRaw('sender_id <> receiver_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                $messages = Message::where(function ($query) use ($item) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $item->user_id);
                })
                    ->orWhere(function ($query) use ($item) {
                        $query->where('sender_id', $item->user_id)
                            ->where('receiver_id', auth()->id());
                    })
                    ->with('receiver')
                    ->whereRaw('sender_id <> receiver_id')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $messages->user = User::find($item->user_id);

                return $messages;
            })
            ->sortByDesc('created_at')
            ->flatten();

        return view('messages.index', ['messages' => $messages]);
    }

    public function show(string $userId): View
    {
        $user = User::findOrFail($userId);
        $messages = Message::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id')
            ->get();

        return view('messages.show', [
            'messages' => $messages,
            'user' => $user,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
