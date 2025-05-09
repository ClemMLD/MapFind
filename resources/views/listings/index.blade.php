<x-app-layout>
    <div id="map" class="w-full h-[calc(100vh-66px)] z-0"></div>

    @if (in_array(auth()->user()->type, ['premium', 'partner']))
        <div id="categoryBox" class="flex flex-col items-center fixed mt-5 left-1/2 transform -translate-x-1/2 space-y-4">
            <button id="dropdownCheckboxButton"
                    class="flex flex-row p-2 bg-yellow-500 hover:bg-yellow-600 transition text-white font-medium rounded-xl px-5 py-2.5 items-center"
                    type="button">
                {{ __('messages.categories') }}
                <x-heroicon-o-chevron-down class="w-4 h-4 ml-2"/>
            </button>

            <div id="dropdownDefaultCheckbox"
                 class="z-10 hidden w-full bg-yellow-500 backdrop-blur-md divide-y rounded-xl shadow-sm overflow-y-auto h-[calc(100vh-400px)]">
                <ul id="categorySelect" class="p-3 space-y-3 text-sm"
                    aria-labelledby="dropdownCheckboxButton">
                </ul>
            </div>
        </div>
    @endif

    <x-floating-button id="recenterButton" class="bottom-0 fixed right-0" icon="map-pin"
                       alt="{{ __('messages.recenter') }}"></x-floating-button>
    <x-floating-button id="cancelButton" class="bottom-20 hidden fixed right-0" icon="trash"
                       alt="{{ __('messages.delete') }}"></x-floating-button>
    <x-floating-button id="createListingButton" class="bottom-40 hidden fixed right-0" icon="plus-circle"
                       alt="{{ __('messages.create') }}"></x-floating-button>

    <x-button id="searchZoneButton" class="fixed bottom-10 left-1/2 transform -translate-x-1/2">
        {{ __('messages.search_here') }}
    </x-button>

    <script>
        let selectedCategories = [];
        document.addEventListener('DOMContentLoaded', async function () {
            await initializeMap();
            await fetchCategories();
        });

        const map = L.map('map', {
            zoomControl: false,
            attributionControl: false
        });

        let marker = null;
        let listingMarkers = L.markerClusterGroup();

        async function fetchCategories() {
            try {
                const response = await fetch(`/categories?language={{ app()->getLocale() }}`);
                const categories = await response.json();
                const categorySelect = document.getElementById('categorySelect');

                categories.forEach(category => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center space-x-2 p-2 rounded-lg dark:text-white';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = category.id;
                    checkbox.style.accentColor = category.color;
                    checkbox.className = 'w-4 h-4 bg-light dark:bg-dark rounded-sm';

                    const colorCircle = document.createElement('span');
                    colorCircle.style.backgroundColor = category.color;
                    colorCircle.className = 'w-4 h-4 rounded-full';
                    li.appendChild(colorCircle);

                    const label = document.createElement('label');
                    label.textContent = category.name;
                    label.className = 'ms-2 text-sm font-medium';

                    li.appendChild(checkbox);
                    li.appendChild(label);
                    categorySelect.appendChild(li);
                });

                categorySelect.addEventListener('change', function () {
                    selectedCategories = Array.from(categorySelect.querySelectorAll('input:checked')).map(input => input.value);
                    fetchListings(map.getCenter().lat, map.getCenter().lng, selectedCategories);
                });

                document.getElementById('dropdownCheckboxButton').addEventListener('click', function () {
                    const dropdown = document.getElementById('dropdownDefaultCheckbox');
                    dropdown.classList.toggle('hidden');
                });
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }

        async function fetchListings(latitude, longitude) {
            listingMarkers.clearLayers();
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker && layer !== marker) {
                    map.removeLayer(layer);
                }
            });

            const categoryParams = selectedCategories.length ? `&categories_id=${selectedCategories}` : '';
            const response = await fetch(`{{ route('listings.index') }}?lat=${latitude}&lng=${longitude}${categoryParams}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const listings = await response.json();

            listings.listings.forEach(listing => {
                const color = listing.boosted ? 'text-yellow-500' : 'text-primary dark:text-primary_dark';
                const customIcon = L.divIcon({
                    className: 'custom-icon',
                    html: `<div class="flex flex-col items-center">
                                <x-heroicon-s-map-pin class="${color} w-10 h-10"/>
                                <p class="bg-primary dark:bg-primary_dark text-white rounded-xl text-center py-1 px-2 font-bold">${listing.title}</p>
                           </div>`,
                    iconSize: [100, 60],
                    iconAnchor: [50, 60]
                });

                const listingMarker = L.marker([listing.latitude, listing.longitude], {icon: customIcon})
                    .on('click', function () {
                        window.location.href = "{{ route('listings.show', ':id') }}".replace(':id', listing.id);
                    });

                if (listing.boosted) {
                    map.addLayer(listingMarker);
                } else {
                    listingMarkers.addLayer(listingMarker);
                }
            });

            map.addLayer(listingMarkers);
        }

        async function initializeMap() {
            if ("geolocation" in navigator) {
                try {
                    const position = await getCurrentPosition();
                    const {latitude, longitude} = position.coords;

                    map.setView([latitude, longitude], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(map);

                    await fetchListings(latitude, longitude);

                    L.circle([latitude, longitude], {
                        color: '#3388ff',
                        fillColor: '#3388ff',
                        fillOpacity: 0.5,
                        radius: 50
                    }).addTo(map);
                } catch (error) {
                    console.error('Error getting current position:', error);
                }
            } else {
                alert("{{ __('messages.geo_error') }}")
            }

            map.on('moveend', function () {
                document.getElementById('searchZoneButton').style.display = 'block';
            });
        }

        async function getCurrentPosition() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {enableHighAccuracy: true});
            });
        }

        map.on('click', function (e) {
            if ("{{$userMaxListings}}") {
                alert("{{ __('messages.max_listings_limit') }}");
            } else {
                if (marker) {
                    map.removeLayer(marker);
                }

                const customIcon = L.divIcon({
                    className: 'custom-icon',
                    html: `<x-heroicon-s-map-pin class="text-secondary dark:text-secondary_dark w-10 h-10"/>`,
                    iconSize: [100, 60],
                    iconAnchor: [20, 50]
                });

                marker = L.marker(e.latlng, {icon: customIcon}).addTo(map);
                showListingButtons();
            }
        });

        function showListingButtons() {
            document.getElementById('createListingButton').style.display = 'block';
            document.getElementById('cancelButton').style.display = 'block';
        }

        function hideListingButtons() {
            document.getElementById('createListingButton').style.display = 'none';
            document.getElementById('cancelButton').style.display = 'none';
        }

        document.getElementById('createListingButton').onclick = function () {
            if (marker) {
                const latlng = marker.getLatLng();
                window.location.href = `{{ route('listings.create') }}?latitude=${latlng.lat}&longitude=${latlng.lng}`;
            }
        }

        document.getElementById('cancelButton').onclick = function () {
            if (marker) {
                map.removeLayer(marker);
                marker = null;
                hideListingButtons();
            }
        }

        document.getElementById('recenterButton').onclick = function () {
            map.locate({setView: true, maxZoom: 16});
        }

        document.getElementById('searchZoneButton').onclick = function () {
            const center = map.getCenter();
            fetchListings(center.lat, center.lng);
            document.getElementById('searchZoneButton').style.display = 'none';
        }
    </script>
</x-app-layout>