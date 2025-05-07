<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.create_listing') }}
        </h2>
    </x-slot>
    <form class="flex flex-col lg:flex-row flex-grow justify-around bg-primary dark:bg-primary_dark rounded-2xl m-4 p-12 space-x-0 space-y-6 lg:space-y-0 lg:space-x-6 lg:h-full h-full">
        <div class="flex flex-col justify-center overflow-y-auto space-y-2 w-full lg:w-1/2 p-2">
            <x-input-label for="title" :value="__('messages.title')"/>
            <x-text-input id="title" type="text" name="title" required/>
            <x-input-label for="description" :value="__('messages.description')"/>
            <x-text-area id="description" type="text" name="description" required></x-text-area>
            <x-input-label for="category" :value="__('messages.category')"/>
            <select id="category" name="category"
                    class="text-sm text-white rounded-xl block w-full p-2.5 bg-secondary dark:bg-secondary_dark focus:ring-2 focus:ring-tertiary dark:focus:ring-tertiary_dark focus:border-none border-transparent focus:border-tertiary focus:dark:border-tertiary_dark"
                    required>
                @foreach($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
            <x-input-label for="address" :value="__('messages.address')"/>
            <x-text-input id="address" type="text" name="address" required/>
            <x-input-label for="price" :value="__('messages.price')"/>
            <x-text-input id="price" type="number" name="price" required/>
            <x-input-label for="currency" :value="__('messages.currency')"/>
            <select id="currency" name="currency"
                    class="text-sm text-white rounded-xl block w-full p-2.5 bg-secondary dark:bg-secondary_dark focus:ring-2 focus:ring-tertiary dark:focus:ring-tertiary_dark focus:border-none border-transparent focus:border-tertiary focus:dark:border-tertiary_dark"
                    required>
                @foreach($currencies as $currency)
                    <option value="{{ $currency }}">{{ $currency }}</option>
                @endforeach
            </select>
        </div>
        <div class="border-2 rounded-lg border-tertiary dark:border-tertiary_dark h-full lg:h-auto lg:w-0 w-full dark:border-dark"></div>
        <div class="flex flex-col w-full md:w-1/2 justify-center items-center self-center space-y-4">
            <input type="file" id="images" name="images" class="hidden" accept="image/png, image/jpeg"
                   @if(in_array(auth()->user()->type, ['premium', 'partner'])) multiple @endif>
            <div id="imagePreviewContainer"
                 class="flex flex-row space-x-2 overflow-x-scroll w-full max-w-full"></div>
            @if(in_array(auth()->user()->type, ['premium', 'partner']))
                <x-button onclick="showImage()"
                          icon="photo"
                          type="button"
                          class="!bg-yellow-500 hover:!bg-yellow-600">
                    {{ __('messages.add_images') }}
                </x-button>
            @else
                <x-button onclick="showImage()"
                          ype="button"
                          icon="photo">
                    {{ __('messages.add_image') }}
                </x-button>
            @endif
            <x-button icon="check" type="button" onclick="create()">
                {{ __('messages.create') }}
            </x-button>
        </div>
    </form>
</x-app-layout>

<script>
    const addedImages = [];

    function showImage() {
        document.getElementById('images').click();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            create();
        });
    });

    document.getElementById('images').addEventListener('input', function () {
        const files = this.files;
        const userType = "{{ auth()->user()->type }}";
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagesCount = addedImages.length;

        if (userType === 'standard' && imagesCount >= 1) {
            alert('{{ __('messages.standard_images_limit') }}');
            return;
        } else if (imagesCount >= 10) {
            alert('{{ __('messages.max_images_limit') }}');
            return;
        }

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('relative', 'w-64', 'md:w-96', 'shrink-0', 'object-fill');
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('object-cover', 'rounded-lg', 'w-full', 'h-48', 'md:h-64');

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('absolute', 'top-2', 'right-2', 'bg-red-600', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center');
                removeBtn.innerHTML = `<img src="{{ asset('vendor/blade-heroicons/o-minus.svg') }}" class="invert w-4 h-4">`;
                removeBtn.addEventListener('click', function () {
                    imgContainer.remove();
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                imagePreviewContainer.appendChild(imgContainer);
            };
            imagePreviewContainer.classList.remove('hidden');
            reader.readAsDataURL(file);
            addedImages.push(file);
        });
    });

    const create = async () => {
        const formData = new FormData();

        formData.append('title', document.getElementById('title').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('category_id', document.getElementById('category').value);
        formData.append('price', document.getElementById('price').value);
        formData.append('currency', currency);
        formData.append('address', document.getElementById('address').value);
        formData.append('currency', document.getElementById('currency').value);
        formData.append('latitude', "{{ $latitude }}");
        formData.append('longitude', "{{ $longitude }}");

        for (let i = 0; i < addedImages.length; i++) {
            formData.append('image' + i, addedImages[i]);
        }

        try {
            const response = await fetch('{{ route('listings.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (response.status === 201) {
                window.location.href = '{{ route('listings.index') }}';
            } else if (response.status === 409) {
                alert('{{ __('messages.max_listings_limit') }}');
                window.location.href = '{{ route('listings.index') }}';
            }
        } catch (error) {
            console.error('Error creating event:', error);
        }
    };
</script>
