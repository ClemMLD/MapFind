<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Messages
        </h2>
    </x-slot>

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if($messages->isEmpty())
        <div class="text-white flex items-center justify-center h-screen">
            <p class="bg-primary dark:bg-primary_dark rounded-lg p-4">
                No messages found.
            </p>
        </div>
    @else
        <ul class="list-group m-12 space-y-4">
            @foreach($messages as $message)
                <a href="{{ route('messages.show', [$message->user->id]) }}" class="block transition">
                    <li class="list-group-item bg-primary dark:bg-primary_dark hover:bg-secondary dark:hover:bg-secondary_dark shadow transition rounded-lg p-4">
                        <p class="font-bold text-white">{{ $message->user->name }} </p>
                        <br>
                        <p class="text-white">{{ __('messages.last_message') }}
                            : {{ $message->content }}</p>
                    </li>
                </a>
            @endforeach
        </ul>
    @endif
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ auth()->user()->id }};
        const messageShowRoute = "{{ route('messages.show', ':id') }}";

        window.Echo.private(`message.${userId}`)
            .listen('MessageSent', (e) => {
                const messageContainer = document.querySelector('.messages');
                const userIdToCheck = e.message.sender_id === userId ? e.message.receiver_id : e.message.sender_id;
                const existingMessage = messageContainer.querySelector(`#user-${userIdToCheck}`);
                const nickname = e.message.sender_id === userId ? e.message.receiver.nickname : e.message.sender.nickname;
                const messageContent = `
                <div class="dark:text-white font-semibold">${nickname}</div>
                <div class="dark:text-white">{{ __('messages.last_message') }} : ${e.message.content}</div>
            `;

                if (existingMessage) {
                    existingMessage.innerHTML = messageContent;
                    messageContainer.prepend(existingMessage);
                } else {
                    const newMessage = document.createElement('div');
                    newMessage.classList.add('list-group-item', 'bg-primary', 'dark:bg-primary_dark', 'hover:bg-secondary', 'dark:hover:bg-secondary_dark', 'shadow', 'transition', 'rounded-lg', 'p-4');
                    newMessage.style.cursor = 'pointer';
                    newMessage.setAttribute('id', `user-${userIdToCheck}`);
                    newMessage.onclick = () => window.location.href = messageShowRoute.replace(':id', userIdToCheck);
                    newMessage.innerHTML = messageContent;
                    messageContainer.prepend(newMessage);
                }
            });
    });
</script>