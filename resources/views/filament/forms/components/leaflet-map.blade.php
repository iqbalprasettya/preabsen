<div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />

    <div id="map" style="height: 400px;" wire:ignore></div>

    <div class="mt-2">
        <button type="button" id="setLocationBtn"
            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-primary-600 border border-transparent rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:hover:bg-primary-500 dark:focus:ring-offset-dark-950">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            Set Lokasi
        </button>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek koordinat yang ada di form terlebih dahulu
            const latSelectors = [
                'input[name="latitude"]',
                'input[name="data[latitude]"]',
                '#data\\.latitude',
                '[wire\\:model\\.live="data.latitude"]'
            ];
            const lngSelectors = [
                'input[name="longitude"]',
                'input[name="data[longitude]"]',
                '#data\\.longitude',
                '[wire\\:model\\.live="data.longitude"]'
            ];

            const existingLat = document.querySelector(latSelectors.join(', '))?.value;
            const existingLng = document.querySelector(lngSelectors.join(', '))?.value;

            // Inisialisasi map dengan koordinat yang ada jika tersedia
            const initialLat = existingLat ? parseFloat(existingLat) : -6.2088;
            const initialLng = existingLng ? parseFloat(existingLng) : 106.8456;
            const initialZoom = existingLat && existingLng ? 17 : 13;

            const map = L.map('map').setView([initialLat, initialLng], initialZoom);
            let currentLat = existingLat ? parseFloat(existingLat) : null;
            let currentLng = existingLng ? parseFloat(existingLng) : null;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = null;
            let circle = null;

            // Fungsi untuk memperbarui nilai form
            function updateFormValues(lat, lng) {
                const latInput = document.querySelector(latSelectors.join(', '));
                const lngInput = document.querySelector(lngSelectors.join(', '));

                if (latInput && lngInput) {
                    latInput.value = lat.toFixed(8);
                    lngInput.value = lng.toFixed(8);

                    ['input', 'change'].forEach(eventType => {
                        latInput.dispatchEvent(new Event(eventType, {
                            bubbles: true
                        }));
                        lngInput.dispatchEvent(new Event(eventType, {
                            bubbles: true
                        }));
                    });

                    latInput.style.backgroundColor = '#e8f5e9';
                    lngInput.style.backgroundColor = '#e8f5e9';

                    setTimeout(() => {
                        latInput.style.backgroundColor = '';
                        lngInput.style.backgroundColor = '';
                    }, 500);

                    console.log('Form values updated:', {
                        lat: lat.toFixed(8),
                        lng: lng.toFixed(8),
                        latInput: latInput.value,
                        lngInput: lngInput.value,
                        latSelector: latInput.getAttribute('name'),
                        lngSelector: lngInput.getAttribute('name')
                    });
                } else {
                    console.error('Input fields not found. Tried selectors:', {
                        latitude: latSelectors,
                        longitude: lngSelectors
                    });
                }
            }

            // Fungsi untuk mendapatkan radius
            function getRadius() {
                const radiusSelectors = [
                    'input[name="radius"]',
                    'input[name="data[radius]"]',
                    '#data\\.radius',
                    '[wire\\:model\\.live="data.radius"]'
                ];
                const radiusInput = document.querySelector(radiusSelectors.join(', '));
                return parseInt(radiusInput?.value) || 100;
            }

            // Fungsi untuk memperbarui peta
            function updateMapFeatures(lat, lng, radius) {
                if (marker) map.removeLayer(marker);
                if (circle) map.removeLayer(circle);

                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                circle = L.circle([lat, lng], {
                    radius: radius,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.2,
                    color: '#3b82f6',
                    weight: 1
                }).addTo(map);

                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    currentLat = position.lat;
                    currentLng = position.lng;
                    circle.setLatLng(position);
                    updateFormValues(currentLat, currentLng);
                });

                map.setView([lat, lng], 17);
            }

            // Event listener untuk tombol Set Lokasi
            document.getElementById('setLocationBtn').addEventListener('click', function() {
                if (currentLat && currentLng) {
                    updateFormValues(currentLat, currentLng);
                } else {
                    alert('Silakan pilih lokasi di peta terlebih dahulu');
                }
            });

            // Event listener untuk klik peta
            map.on('click', function(e) {
                currentLat = e.latlng.lat;
                currentLng = e.latlng.lng;
                const radius = getRadius();
                updateMapFeatures(currentLat, currentLng, radius);
                updateFormValues(currentLat, currentLng);
            });

            // Event listener untuk perubahan input manual
            document.addEventListener('input', function(e) {
                if (e.target.matches('[name="latitude"], [name="longitude"], [name="data[latitude]"], [name="data[longitude]"], [name="radius"], [name="data[radius]"]')) {
                    const latInput = document.querySelector('[name="latitude"], [name="data[latitude]"]');
                    const lngInput = document.querySelector('[name="longitude"], [name="data[longitude]"]');
                    const lat = parseFloat(latInput?.value);
                    const lng = parseFloat(lngInput?.value);
                    const radius = getRadius();

                    if (!isNaN(lat) && !isNaN(lng)) {
                        currentLat = lat;
                        currentLng = lng;
                        updateMapFeatures(lat, lng, radius);
                    }
                }
            });

            // Inisialisasi locate control
            const lc = L.control.locate({
                position: 'topleft',
                strings: {
                    title: "Deteksi lokasi saya"
                },
                flyTo: true,
                initialZoomLevel: 17,
                onLocationFound: function(e) {
                    currentLat = e.latitude;
                    currentLng = e.longitude;
                    updateMapFeatures(e.latitude, e.longitude, getRadius());
                    updateFormValues(currentLat, currentLng);
                }
            }).addTo(map);

            // Jika ada koordinat yang tersedia, langsung tampilkan marker
            if (existingLat && existingLng) {
                currentLat = parseFloat(existingLat);
                currentLng = parseFloat(existingLng);
                
                if (!isNaN(currentLat) && !isNaN(currentLng)) {
                    console.log('Menampilkan lokasi yang tersedia:', { currentLat, currentLng });
                    updateMapFeatures(currentLat, currentLng, getRadius());
                }
            }

            // Fix map size
            setTimeout(() => {
                map.invalidateSize();
                if (currentLat && currentLng) {
                    map.setView([currentLat, currentLng], 17);
                }
            }, 500);
        });
    </script>
</div>
