@extends('layouts.app')

@push('styles')
    <style>
        .hero-image-container {
            height: 680px;
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
        }

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
@endpush

@section('content')
    <!-- Hero Section -->
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

    <!-- Map/Order Section -->
    <section id="quote" class="padding-small">
        <div class="container text-center">
            <h3 class="display-6 fw-semibold mb-4">Tentukan Lokasi</h3>
            <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>

            <button id="useMyLocation" class="btn btn-primary mb-3">
                <i class="fas fa-location-arrow"></i> Gunakan Lokasi Saya
            </button>

            <div id="routeInfo" class="alert alert-info mb-3" style="display: none;"></div>

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
                <strong>Tarif Dasar: Rp1000,00</strong>
            </div>

            <a id="order" class="btn btn-success mt-3">
                <i class="fas fa-location-arrow"></i> Pesan Via Whatsapp
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAP_API_KEY') }}&libraries=places"></script>

    <script>
        $(document).ready(function() {
            // Constants
            const BASECAMP_LAT = -8.171121371857444;
            const BASECAMP_LNG = 113.7233082269837;
            const FLAT_FEE = 1000; // New flat fee
            const PER_KM_RATE = 2000;

            // Map variables
            let map;
            let markerAwal = null;
            let markerAkhir = null;
            let directionsService;
            let directionsRenderer;

            initMap();

            // Initialize Google Maps
            function initMap() {
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#3388ff',
                        strokeWeight: 6
                    }
                });

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: BASECAMP_LAT,
                        lng: BASECAMP_LNG
                    },
                    zoom: 13
                });

                directionsRenderer.setMap(map);

                // Add click listener to map for destination selection
                map.addListener('click', function(e) {
                    if (!markerAkhir) {
                        const lat = e.latLng.lat();
                        const lng = e.latLng.lng();
                        createMarker(lat, lng, false);
                        $('#lat_akhir').val(lat);
                        $('#lng_akhir').val(lng);
                        getAddressFromLatLng(lat, lng, 'lokasi_akhir');
                    }
                });

                // Setup autocomplete for inputs
                setupAutocomplete('lokasi_awal', true);
                setupAutocomplete('lokasi_akhir', false);
            }

            // Helper function: Calculate distance between two points using Haversine formula
            function calculateDistance(lat1, lng1, lat2, lng2) {
                const R = 6371; // Earth's radius in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;
                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // Calculate total price with the new pricing model
            function calculatePrice(routeDistance) {
                // Simple calculation: flat fee + per km rate
                return FLAT_FEE + (routeDistance * PER_KM_RATE);
            }

            // Get address from coordinates using Google Geocoder
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
                    } else {
                        $(`#${inputId}`).val(`${lat}, ${lng}`);
                    }
                    calculateRoute();
                });
            }

            // Setup autocomplete for location inputs
            function setupAutocomplete(inputId, isAwal) {
                const input = document.getElementById(inputId);
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['geocode', 'establishment'],
                    fields: ['geometry', 'name', 'formatted_address']
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();

                    // Validate selection
                    if (!place.geometry || !place.formatted_address ||
                        place.formatted_address.toLowerCase().includes('undefined')) {
                        input.value = '';
                        alert('Mohon pilih lokasi yang valid dari daftar Google Maps');
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

            // Calculate and display route between markers
            function calculateRoute() {
                if (!markerAwal || !markerAkhir) return;

                const startPos = markerAwal.getPosition();
                const endPos = markerAkhir.getPosition();

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

                        // Calculate total price with new model
                        const totalPrice = Math.ceil(calculatePrice(parseFloat(routeDistance)) / 100) * 100;

                        // Update route info display with simplified pricing
                        $('#routeInfo').html(
                            `Jarak: <span id="distance">${routeDistance}</span> km<br>
                    Estimasi waktu: <span id="duration">${duration}</span> menit<br>
                    Harga: Rp${FLAT_FEE.toLocaleString()} (tarif dasar) + Rp${(routeDistance * PER_KM_RATE).toLocaleString()} (${routeDistance} km × Rp${PER_KM_RATE.toLocaleString()})<br>
                    Harga Total: Rp<span id="totalPrice">${totalPrice.toLocaleString()}</span>`
                        ).show();
                    }
                });
            }

            // Create marker on the map
            function createMarker(lat, lng, isAwal) {
                const position = new google.maps.LatLng(lat, lng);
                const markerColor = isAwal ? '#00FF00' : '#FF0000';

                // SVG marker for better visibility
                const svgMarker = "data:image/svg+xml," + encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="${markerColor}" 
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

                // Set marker and add drag event handler
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

            // Get user's current location
            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            $('#lat_awal').val(lat);
                            $('#lng_awal').val(lng);

                            map.setCenter({
                                lat,
                                lng
                            });
                            map.setZoom(15);
                            createMarker(lat, lng, true);
                            getAddressFromLatLng(lat, lng, 'lokasi_awal');
                        },
                        function(error) {
                            alert("Error mendapatkan lokasi: " + error.message);
                        }
                    );
                } else {
                    alert("Geolocation tidak didukung oleh browser ini");
                }
            }

            // Validate location coordinates
            async function validateLocation(lat, lng) {
                if (!lat || !lng || isNaN(lat) || isNaN(lng)) return false;

                return new Promise((resolve) => {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        location: {
                            lat,
                            lng
                        }
                    }, (results, status) => {
                        resolve(
                            status === google.maps.GeocoderStatus.OK &&
                            results &&
                            results.length > 0 &&
                            results[0].geometry &&
                            results[0].geometry.location_type !== 'APPROXIMATE' &&
                            results[0].formatted_address &&
                            !results[0].formatted_address.toLowerCase().includes(
                                'undefined')
                        );
                    });
                });
            }

            // Event Handlers
            $('#useMyLocation').click(getUserLocation);

            $('#order').click(async function() {
                const lat_awal = parseFloat($('#lat_awal').val());
                const lng_awal = parseFloat($('#lng_awal').val());
                const lat_akhir = parseFloat($('#lat_akhir').val());
                const lng_akhir = parseFloat($('#lng_akhir').val());
                const price = $('#totalPrice').text();

                // Validate both locations
                const isStartValid = await validateLocation(lat_awal, lng_awal);
                const isEndValid = await validateLocation(lat_akhir, lng_akhir);

                if (!isStartValid || !isEndValid) {
                    alert('Lokasi tidak valid. Pastikan menggunakan lokasi tepat dari Google Maps.');
                    return;
                }

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Apakah anda yakin ingin memesan jasa ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, pesan!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('taxi.pesan') }}`,
                            method: 'POST',
                            data: {
                                // csrf
                                _token: '{{ csrf_token() }}',
                                total_harga: parseInt(price.replace(/\D/g, '')),
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: 'Pesanan berhasil dibuat, Anda akan diarahkan ke WhatsApp Admin',
                                        icon: 'success'
                                    }).then(() => {
                                        const message =
                                            `Hii, saya baru saja memesan To Help untuk meminta bantuan\n\n- Mobil\nTitik Penjemputan : ${$('#lokasi_awal').val()}\nTitik Pengantaran : ${$('#lokasi_akhir').val()}\nHarga : ${$('#totalPrice').text()}`;
                                        window.open(
                                            `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                                            '_blank'
                                        );
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Pesanan gagal dibuat, silahkan coba lagi',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Pesanan gagal dibuat, silahkan coba lagi',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });

                // Create WhatsApp message and open link
                // const message =
                //     `Hii, saya baru saja memesan To Help untuk meminta bantuan\n\n- Ojek\nTitik Penjemputan : ${$('#lokasi_awal').val()}\nTitik Pengantaran : ${$('#lokasi_akhir').val()}\nHarga : ${$('#totalPrice').text()}`;
                // window.open(
                //     `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                //     '_blank'
                // );
            });
        });
    </script>
@endpush
