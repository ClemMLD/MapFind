<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StripeWebhookRequest;

class SubscriptionWebhookController extends Controller
{
    public function stripe(StripeWebhookRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = data_get($data, 'data.object.lines.data.0.metadata.user_id' ?? 0);

        $this->subscribeUser($userId);

        return response()->json('', 204);
    }

    private function subscribeUser(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->type === 'premium') {
            return;
        }

        $user->update([
            'subscribed_at' => now(),
            'type' => 'premium',
            'max_listings' => 5,
            'boosts' => $user->boosts + 5,
        ]);
    }
}
