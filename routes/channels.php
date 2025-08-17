<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes([
    'middleware' => ['web', 'auth:sanctum'],
]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
