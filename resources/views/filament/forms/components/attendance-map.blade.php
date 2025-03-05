@php
    $record = $getRecord();
@endphp

<div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Peta Check In -->
        <div>
            <h3 class="text-sm font-medium mb-2">Check In</h3>
            <div id="check-in-map" style="height: 400px;" class="rounded-lg border border-gray-300"></div>
        </div>

        <!-- Peta Check Out -->
        <div>
            <h3 class="text-sm font-medium mb-2">Check Out</h3>
            <div id="check-out-map" style="height: 400px;" class="rounded-lg border border-gray-300"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default koordinat Jakarta (jika tidak ada data)
            const defaultLat = -6.16559260;
            const defaultLong = 106.82375640;

            // Ambil data dari form
            const checkInLatInput = document.querySelector('[name="data[check_in_latitude]"]');
            const checkInLongInput = document.querySelector('[name="data[check_in_longitude]"]');
            const checkOutLatInput = document.querySelector('[name="data[check_out_latitude]"]');
            const checkOutLongInput = document.querySelector('[name="data[check_out_longitude]"]');

            // Koordinat Check In
            let checkInLat = defaultLat;
            let checkInLong = defaultLong;

            if (checkInLatInput && checkInLatInput.value && checkInLongInput && checkInLongInput.value) {
                checkInLat = parseFloat(checkInLatInput.value);
                checkInLong = parseFloat(checkInLongInput.value);
            }
            @if ($record && $record->check_in_latitude && $record->check_in_longitude)
                checkInLat = {{ $record->check_in_latitude }};
                checkInLong = {{ $record->check_in_longitude }};
            @endif

            // Koordinat Check Out
            let checkOutLat = defaultLat;
            let checkOutLong = defaultLong;

            if (checkOutLatInput && checkOutLatInput.value && checkOutLongInput && checkOutLongInput.value) {
                checkOutLat = parseFloat(checkOutLatInput.value);
                checkOutLong = parseFloat(checkOutLongInput.value);
            }
            @if ($record && $record->check_out_latitude && $record->check_out_longitude)
                checkOutLat = {{ $record->check_out_latitude }};
                checkOutLong = {{ $record->check_out_longitude }};
            @endif

            // Inisialisasi peta Check In
            const checkInMap = L.map('check-in-map').setView([checkInLat, checkInLong], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(checkInMap);
            L.marker([checkInLat, checkInLong])
                .addTo(checkInMap)
                .bindPopup('Lokasi Check In');

            // Inisialisasi peta Check Out
            const checkOutMap = L.map('check-out-map').setView([checkOutLat, checkOutLong], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(checkOutMap);
            L.marker([checkOutLat, checkOutLong])
                .addTo(checkOutMap)
                .bindPopup('Lokasi Check Out');

            // Update peta saat nilai input berubah
            if (checkInLatInput && checkInLongInput) {
                const updateCheckInMap = function() {
                    const lat = parseFloat(checkInLatInput.value) || defaultLat;
                    const long = parseFloat(checkInLongInput.value) || defaultLong;

                    checkInMap.setView([lat, long], 15);
                    // Hapus marker lama
                    checkInMap.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            checkInMap.removeLayer(layer);
                        }
                    });
                    // Tambah marker baru
                    L.marker([lat, long]).addTo(checkInMap).bindPopup('Lokasi Check In');
                };

                checkInLatInput.addEventListener('change', updateCheckInMap);
                checkInLongInput.addEventListener('change', updateCheckInMap);
            }

            if (checkOutLatInput && checkOutLongInput) {
                const updateCheckOutMap = function() {
                    const lat = parseFloat(checkOutLatInput.value) || defaultLat;
                    const long = parseFloat(checkOutLongInput.value) || defaultLong;

                    checkOutMap.setView([lat, long], 15);
                    // Hapus marker lama
                    checkOutMap.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            checkOutMap.removeLayer(layer);
                        }
                    });
                    // Tambah marker baru
                    L.marker([lat, long]).addTo(checkOutMap).bindPopup('Lokasi Check Out');
                };

                checkOutLatInput.addEventListener('change', updateCheckOutMap);
                checkOutLongInput.addEventListener('change', updateCheckOutMap);
            }
        });
    </script>
</div>
