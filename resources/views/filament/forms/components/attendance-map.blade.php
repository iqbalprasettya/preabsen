@php
    $record = $getRecord();
@endphp

<div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium mb-2">Check In</h3>
            <div id="check-in-map" style="height: 400px;" class="rounded-lg border border-gray-300"></div>
        </div>

        <div>
            <h3 class="text-sm font-medium mb-2">Check Out</h3>
            <div id="check-out-map" style="height: 400px;" class="rounded-lg border border-gray-300"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi peta default
            const defaultLat = -6.200000; // Latitude default (Jakarta)
            const defaultLong = 106.816666; // Longitude default (Jakarta)

            // Inisialisasi peta Check In
            const checkInMap = L.map('check-in-map').setView([defaultLat, defaultLong], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(checkInMap);

            // Inisialisasi peta Check Out
            const checkOutMap = L.map('check-out-map').setView([defaultLat, defaultLong], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(checkOutMap);

            @if ($record)
                @if ($record->check_in_latitude && $record->check_in_longitude)
                    // Update peta Check In dengan data yang ada
                    checkInMap.setView([{{ $record->check_in_latitude }}, {{ $record->check_in_longitude }}], 15);
                    L.marker([{{ $record->check_in_latitude }}, {{ $record->check_in_longitude }}])
                        .addTo(checkInMap)
                        .bindPopup('Lokasi Check In');
                @endif

                @if ($record->check_out_latitude && $record->check_out_longitude)
                    // Update peta Check Out dengan data yang ada
                    checkOutMap.setView([{{ $record->check_out_latitude }}, {{ $record->check_out_longitude }}],
                        15);
                    L.marker([{{ $record->check_out_latitude }}, {{ $record->check_out_longitude }}])
                        .addTo(checkOutMap)
                        .bindPopup('Lokasi Check Out');
                @endif
            @endif

            // Ambil referensi input form
            const checkInLatInput = document.querySelector('[name="data[check_in_latitude]"]');
            const checkInLongInput = document.querySelector('[name="data[check_in_longitude]"]');
            const checkOutLatInput = document.querySelector('[name="data[check_out_latitude]"]');
            const checkOutLongInput = document.querySelector('[name="data[check_out_longitude]"]');

            // Update peta Check In saat input berubah
            if (checkInLatInput && checkInLongInput) {
                const updateCheckInMap = function() {
                    const lat = parseFloat(checkInLatInput.value);
                    const long = parseFloat(checkInLongInput.value);

                    if (!isNaN(lat) && !isNaN(long)) {
                        checkInMap.setView([lat, long], 15);
                        // Hapus marker lama
                        checkInMap.eachLayer(function(layer) {
                            if (layer instanceof L.Marker) {
                                checkInMap.removeLayer(layer);
                            }
                        });
                        // Tambah marker baru
                        L.marker([lat, long]).addTo(checkInMap).bindPopup('Lokasi Check In');
                    }
                };

                checkInLatInput.addEventListener('change', updateCheckInMap);
                checkInLongInput.addEventListener('change', updateCheckInMap);
            }

            // Update peta Check Out saat input berubah
            if (checkOutLatInput && checkOutLongInput) {
                const updateCheckOutMap = function() {
                    const lat = parseFloat(checkOutLatInput.value);
                    const long = parseFloat(checkOutLongInput.value);

                    if (!isNaN(lat) && !isNaN(long)) {
                        checkOutMap.setView([lat, long], 15);
                        // Hapus marker lama
                        checkOutMap.eachLayer(function(layer) {
                            if (layer instanceof L.Marker) {
                                checkOutMap.removeLayer(layer);
                            }
                        });
                        // Tambah marker baru
                        L.marker([lat, long]).addTo(checkOutMap).bindPopup('Lokasi Check Out');
                    }
                };

                checkOutLatInput.addEventListener('change', updateCheckOutMap);
                checkOutLongInput.addEventListener('change', updateCheckOutMap);
            }
        });
    </script>
</div>
