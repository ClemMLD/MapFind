<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight cursor-pointer"
            onclick="window.location.href='{{ route('users.show', $user->id) }}'">
            {{ $user->name }}
        </h2>
    </x-slot>

    <meta name="user-id" content="{{ auth()->id() }}">

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="m-4 bg-primary dark:bg-primary_dark rounded-2xl p-12 flex flex-col space-y-4 flex-grow justify-between">
        <ul class="list-group space-y-4" style="max-height: 400px; overflow-y: auto;">
            @foreach($messages as $message)
                <li class="list-group-item rounded-2xl p-4 bg-secondary dark:bg-secondary_dark w-4/12 {{ $message->sender_id != auth()->user()->id ? 'mr-auto' : 'ml-auto' }}">
                    <p class="font-bold text-white"> {{ $message->content }} </p>
                </li>
            @endforeach
        </ul>
        <div class="flex flex-row space-x-4">
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <x-text-area name="content" class="w-full"/>
            <x-floating-button icon="paper-airplane" type="submit" onclick="sendMessage()">Send</x-floating-button>
        </div>
    </div>

    <script>
        const messageList = document.querySelector('.list-group');

        document.addEventListener('DOMContentLoaded', function () {
            const userId = {{ auth()->user()->id }};
            messageList.scrollTop = messageList.scrollHeight;

            window.Echo.private(`chat.${userId}`)
                .listen('MessageSent', (response) => {
                    displayMessage(response.message);
                });
        });

        function sendMessage() {
            const receiverId = document.querySelector('input[name="receiver_id"]').value;
            const content = document.querySelector('textarea[name="content"]').value;

            axios.post('/messages', {
                receiver_id: receiverId,
                content: content
            })
                .then((response) => {
                    displayMessage(response.data);
                });
        }

        function displayMessage(response) {
            const messageItem = document.createElement('li');
            messageItem.classList.add('list-group-item', 'rounded-2xl', 'p-4', 'bg-secondary', 'dark:bg-secondary_dark', 'w-4/12');
            messageItem.classList.add(response.sender_id != {{ auth()->id() }} ? 'mr-auto' : 'ml-auto');
            messageItem.innerHTML = `<p class="font-bold text-white">${response.content}</p>`;
            messageList.appendChild(messageItem);
            messageList.scrollTop = messageList.scrollHeight;
            document.querySelector('textarea[name="content"]').value = '';
        }
    </script>
</x-app-layout>