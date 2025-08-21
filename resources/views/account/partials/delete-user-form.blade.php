<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('messages.delete_account') }}
        </h2>

        <p class="mt-1 text-sm text-white">
            {{ __('messages.delete_account_description') }}
        </p>
    </header>

    <x-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        :danger="true"
    >{{ __('messages.delete_account') }}</x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('account.destroy', auth()->user()) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white">
                {{ __('messages.delete_account_confirmation') }}
            </h2>

            <p class="mt-1 text-sm text-white">
                {{ __('messages.delete_account_description') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" :value="__('messages.password')" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('messages.password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-4 flex flex-row space-x-2">
                <x-button type="button" x-on:click="$dispatch('close')">
                    {{ __('messages.cancel') }}
                </x-button>

                <x-button danger>
                    {{ __('messages.delete_account') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
