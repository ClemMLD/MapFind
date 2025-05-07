<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Avatar') }}
        </h2>

        <p class="mt-1 text-sm text-white">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <div class="relative w-64 h-64 m-4 mx-auto">
        @if (auth()->user()->image)
            <img id="user-avatar" src="{{ '/storage/' . auth()->user()->image }}" alt="{{ auth()->user()->name }}"
                 class="w-full h-full p-5 rounded-full object-cover">
        @else
            <x-heroicon-o-user-circle id="user-avatar" class="text-white"/>
        @endif

        <x-floating-button class="absolute top-0 left-0" icon="pencil" onclick="document.getElementById('upload-avatar').click()"></x-floating-button>
        <input id="upload-avatar" type="file" class="hidden" onchange="uploadAvatar(event)">

        <x-floating-button class="absolute top-0 right-0" icon="trash" onclick="deleteAvatar()"></x-floating-button>
    </div>
</section>

<script>
    async function uploadAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('avatar', file);

        const response = await fetch('{{ route('account.avatar') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        if (response.status === 201) {
            window.location.reload();
        }
    }

    async function deleteAvatar() {
        const response = await fetch('{{ route('account.avatar') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.status === 204) {
            window.location.reload();
        }
    }
</script>