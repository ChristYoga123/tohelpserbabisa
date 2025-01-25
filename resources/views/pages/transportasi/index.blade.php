@extends('layouts.app')
@push('styles')
    <style>
        .hero-image-container {
            height: 680px;
            /* Anda bisa menyesuaikan tinggi sesuai kebutuhan */
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
        }
    </style>
@endpush
@section('content')
    <section id="billboard">
        <div class="row align-items-center g-0 bg-secondary">
            <div class="col-lg-6">
                <div class="m-4 p-4 m-lg-5 p-lg-5">
                    <h6 class="text-white"><span class="text-primary">Transportasi</span>(Ngojek)</h6>
                    <h2 class="display-4 fw-bold text-white my-4">Perjalanan Nyaman, Harga Bersahabat</h2>
                    <a href="#quote" class="btn btn-light btn-bg btn-slide hover-slide-right mt-4">
                        <span>Silahkan Pesan</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-container">
                    <img src="{{ asset('images/hero-ojek.jpg') }}" alt="img" class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </section>

    <section id="quote" class="padding-small">
        <div class="container text-center">
            <h3 class="display-6 fw-semibold mb-4">Tentukan Lokasi</h3>
            <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>
            <button id="useMyLocation" class="btn btn-primary mb-3">
                <i class="fas fa-location-arrow"></i> Gunakan Lokasi Saya
            </button>
            <div id="routeInfo" class="alert alert-info mb-3" style="display: none;">
                Jarak: <span id="distance">0</span> km
                <br>
                Estimasi waktu: <span id="duration">0</span> menit
                <br>
                Harga Total: Rp<span id="totalPrice">0</span>
            </div>
            <form class="contact-form row mt-5" id="locationForm">
                <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                    <input type="text" name="lokasi_awal" id="lokasi_awal" placeholder="Mendapatkan lokasi Anda..."
                        class="form-control w-100 ps-3 py-2 rounded-0" required>
                    <input type="hidden" id="lat_awal" name="lat_awal">
                    <input type="hidden" id="lng_awal" name="lng_awal">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                    <input type="text" name="lokasi_akhir" id="lokasi_akhir" placeholder="Masukkan Tujuan*"
                        class="form-control w-100 ps-3 py-2 rounded-0" required>
                    <input type="hidden" id="lat_akhir" name="lat_akhir">
                    <input type="hidden" id="lng_akhir" name="lng_akhir">
                </div>
            </form>

            <div class="mt-5 d-flex flex-column">
                <strong>Biaya: Rp<span id="harga-dasar">2000</span>,00/km</strong>
                <strong>Tarif Dasar: Rp7200,00</strong>
                <small class="text-danger">
                    (NB: Jika lokasi basecamp dengan lokasi penjemputan lebih dari 5 km, maka akan dikenakan biaya
                    tambahan sebesar Rp1000,00/km)
                </small>
            </div>
            <a id="order" class="btn btn-success mt-3">
                <i class="fas fa-location-arrow"></i> Pesan Via Whatsapp
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAP_API_KEY') }}&libraries=places"></script>
    <style>
        .leaflet-control-geosearch {
            width: 100%;
            margin: 0 !important;
        }

        .leaflet-control-geosearch form {
            background: #fff;
            padding: 10px;
        }

        .leaflet-control-geosearch form input {
            width: 100%;
            padding: 5px;
        }

        .custom-marker {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }
    </style>

    <script>
        // Replace the Leaflet script tags with Google Maps

        $(document).ready(function() {
            let map;
            let markerAwal = null;
            let markerAkhir = null;
            let directionsService;
            let directionsRenderer;
            const BASECAMP_LAT = -8.171121371857444;
            const BASECAMP_LNG = 113.7233082269837;

            // Initialize the map
            function initMap() {
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true, // We'll use our own markers
                    polylineOptions: {
                        strokeColor: '#3388ff',
                        strokeWeight: 6
                    }
                });

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -8.171121371857444,
                        lng: 113.7233082269837
                    },
                    zoom: 13
                });

                directionsRenderer.setMap(map);

                // Add click listener to map
                map.addListener('click', function(e) {
                    if (!markerAkhir) {
                        const lat = e.latLng.lat();
                        const lng = e.latLng.lng();
                        createMarker(lat, lng, false);
                        $('#lat_akhir').val(lat);
                        $('#lng_akhir').val(lng);
                        getAddressFromLatLng(lat, lng, 'lokasi_akhir');
                        calculateRoute();
                    }
                });

                // Setup autocomplete for both inputs
                setupAutocomplete('lokasi_awal', true);
                setupAutocomplete('lokasi_akhir', false);
            }

            // Calculate distance between two points
            function calculateDistance(lat1, lng1, lat2, lng2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;
                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // Calculate total price
            function calculatePrice(distanceToBasecamp, routeDistance) {
                const baseFare = 7200;
                const perKmRate = 2000;
                let additionalFare = 0;

                if (distanceToBasecamp > 5) {
                    const extraDistance = distanceToBasecamp - 5;
                    additionalFare = extraDistance * 1000;
                }

                return baseFare + (routeDistance * perKmRate) + additionalFare;
            }

            // Get address from coordinates
            function getAddressFromLatLng(lat, lng, inputId) {
                const geocoder = new google.maps.Geocoder();
                const latlng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };

                geocoder.geocode({
                    location: latlng
                }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        $(`#${inputId}`).val(results[0].formatted_address);
                        calculateRoute();
                    } else {
                        $(`#${inputId}`).val(`${lat}, ${lng}`);
                        calculateRoute();
                    }
                });
            }

            // Setup autocomplete
            function setupAutocomplete(inputId, isAwal) {
                const input = document.getElementById(inputId);
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['geocode', 'establishment'], // Restrict to valid map locations
                    fields: ['geometry', 'name', 'formatted_address']
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();

                    // Strict validation checks
                    if (!place.geometry) {
                        // Clear input if no valid place selected
                        input.value = '';
                        alert('Mohon pilih lokasi yang valid dari daftar Google Maps');
                        return;
                    }

                    // Additional validation to ensure it's a real, precise location
                    if (!place.geometry.location ||
                        !place.formatted_address ||
                        place.formatted_address.toLowerCase().includes('undefined')) {
                        input.value = '';
                        alert(
                            'Lokasi yang dipilih tidak valid. Silakan pilih lokasi spesifik dari daftar. Bisa digerakkan marker untuk menyesuaikan lokasi.'
                        );
                        return;
                    }

                    const lat = place.geometry.location.lat();
                    const lng = place.geometry.location.lng();

                    if (isAwal) {
                        $('#lat_awal').val(lat);
                        $('#lng_awal').val(lng);
                    } else {
                        $('#lat_akhir').val(lat);
                        $('#lng_akhir').val(lng);
                    }

                    createMarker(lat, lng, isAwal);
                    map.setCenter(place.geometry.location);
                    calculateRoute();
                });
            }

            // Calculate and display route
            function calculateRoute() {
                if (markerAwal && markerAkhir) {
                    const startPos = markerAwal.getPosition();
                    const endPos = markerAkhir.getPosition();

                    const distanceToBasecamp = calculateDistance(
                        BASECAMP_LAT,
                        BASECAMP_LNG,
                        startPos.lat(),
                        startPos.lng()
                    );

                    const request = {
                        origin: startPos,
                        destination: endPos,
                        travelMode: 'DRIVING'
                    };

                    directionsService.route(request, function(result, status) {
                        if (status === 'OK') {
                            directionsRenderer.setDirections(result);

                            const route = result.routes[0];
                            const routeDistance = (route.legs[0].distance.value / 1000).toFixed(2);
                            const duration = Math.round(route.legs[0].duration.value / 60);

                            const extraBasecampFee = Math.ceil(((distanceToBasecamp - 5) * 1000) / 100) *
                                100;
                            const totalPrice = Math.ceil(calculatePrice(distanceToBasecamp, parseFloat(
                                routeDistance)) / 100) * 100;

                            const basecampInfo = distanceToBasecamp > 5 ?
                                `<br>Jarak basecamp ke lokasi penjemputan: ${distanceToBasecamp.toFixed(2)} km<br>Biaya tambahan: Rp${extraBasecampFee.toLocaleString()} (${(distanceToBasecamp - 5).toFixed(2)} km × Rp1.000)` :
                                `<br>Jarak basecamp ke lokasi penjemputan: ${distanceToBasecamp.toFixed(2)} km`;

                            $('#routeInfo').html(
                                `Jarak: <span id="distance">${routeDistance}</span> km<br>
                Estimasi waktu: <span id="duration">${duration}</span> menit${basecampInfo}<br>
                Harga Total: Rp<span id="totalPrice">${totalPrice.toLocaleString()}</span>`
                            ).show();
                        }
                    });
                }
            }

            // Create marker
            function createMarker(lat, lng, isAwal) {
                const position = new google.maps.LatLng(lat, lng);
                const svgMarker = "data:image/svg+xml," + encodeURIComponent(`
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="${isAwal ? '#00FF00' : '#FF0000'}" 
                  d="M12 2a8 8 0 0 0-7.992 8A12.816 12.816 0 0 0 12 22a12.816 12.816 0 0 0 7.988-12A8 8 0 0 0 12 2zm0 11a3 3 0 1 1 3-3 3 3 0 0 1-3 3z"/>
        </svg>
    `);

                const markerOptions = {
                    position: position,
                    map: map,
                    draggable: true,
                    icon: {
                        url: svgMarker,
                        scaledSize: new google.maps.Size(40, 40),
                        anchor: new google.maps.Point(20, 20)
                    }
                };

                if (isAwal) {
                    if (markerAwal) markerAwal.setMap(null);
                    markerAwal = new google.maps.Marker(markerOptions);

                    markerAwal.addListener('dragend', function() {
                        const pos = markerAwal.getPosition();
                        $('#lat_awal').val(pos.lat());
                        $('#lng_awal').val(pos.lng());
                        getAddressFromLatLng(pos.lat(), pos.lng(), 'lokasi_awal');
                    });
                } else {
                    if (markerAkhir) markerAkhir.setMap(null);
                    markerAkhir = new google.maps.Marker(markerOptions);

                    markerAkhir.addListener('dragend', function() {
                        const pos = markerAkhir.getPosition();
                        $('#lat_akhir').val(pos.lat());
                        $('#lng_akhir').val(pos.lng());
                        getAddressFromLatLng(pos.lat(), pos.lng(), 'lokasi_akhir');
                    });
                }
            }

            // Get user location
            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        $('#lat_awal').val(lat);
                        $('#lng_awal').val(lng);

                        map.setCenter({
                            lat: lat,
                            lng: lng
                        });
                        map.setZoom(15);
                        createMarker(lat, lng, true);
                        getAddressFromLatLng(lat, lng, 'lokasi_awal');
                    }, function(error) {
                        alert("Error mendapatkan lokasi: " + error.message);
                    });
                } else {
                    alert("Geolocation tidak didukung oleh browser ini");
                }
            }

            // Event handlers
            $('#useMyLocation').click(function() {
                getUserLocation();
            });

            // Order button handler
            $('#order').click(function() {
                const lokasi_awal = $('#lokasi_awal').val();
                const lokasi_akhir = $('#lokasi_akhir').val();
                const harga = $('#totalPrice').text();

                if (!lokasi_awal || !lokasi_akhir) {
                    alert('Mohon isi lokasi penjemputan dan pengantaran terlebih dahulu');
                    return;
                }

                const message =
                    `Hii, saya baru saja memesan  To Help untuk meminta bantuan\n\n- Ojek\nTitik Penjemputan : ${lokasi_awal}\nTitik Pengantaran : ${lokasi_akhir}\nHarga : ${harga}`;
                window.open(
                    `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                    '_blank');
            });

            // Initialize the map
            initMap();
        });
    </script>
@endpush
