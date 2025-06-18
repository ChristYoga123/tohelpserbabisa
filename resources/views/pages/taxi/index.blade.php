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
                    <h6 class="text-white"><span class="text-primary">Transportasi</span>(Mobil)</h6>
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
                        class="form-control w-100 ps-3 py-2 rounded-0" required disabled>
                    <input type="hidden" id="lat_awal" name="lat_awal">
                    <input type="hidden" id="lng_awal" name="lng_awal">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                    <input type="text" name="lokasi_akhir" id="lokasi_akhir" placeholder="Masukkan Tujuan*"
                        class="form-control w-100 ps-3 py-2 rounded-0" required disabled>
                    <input type="hidden" id="lat_akhir" name="lat_akhir">
                    <input type="hidden" id="lng_akhir" name="lng_akhir">
                </div>
            </form>
            <!-- Voucher Section -->
            <div class="mt-4 mb-3">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="input-group">
                            <input type="text" id="kode-voucher" class="form-control ps-3 py-2 rounded-0"
                                placeholder="Masukkan kode voucher" aria-label="Kode voucher">
                            <button class="btn btn-primary" type="button" id="apply-voucher">
                                <i class="fas fa-tag"></i> Apply Voucher
                            </button>
                            <button class="btn btn-outline-secondary" type="button" id="reset-voucher"
                                style="display: none;">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        </div>
                        <div id="voucher-info" class="mt-2 text-start" style="display: none;"></div>
                    </div>
                </div>
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
            const BASECAMP_LAT = parseFloat("{{ env('BASECAMP_LAT') }}");
            const BASECAMP_LNG = parseFloat("{{ env('BASECAMP_LONG') }}");
            const BASE_FEE = parseFloat("{{ $tarifDasar->harga }}"); // Base fee for car

            // Tiered pricing for cars
            const TIER_1_MAX = 3; // 1-3 km
            const TIER_1_RATE = 18000; // 18rb/km
            const TIER_2_MAX = 10; // 4-10 km
            const TIER_2_RATE = 5000; // 5rb/km
            const TIER_3_RATE = 4000; // > 10 km rate (4rb/km)

            // Declare voucherDiscount at the script level so it's accessible in multiple functions
            let voucherDiscount = 0;
            let appliedVoucherCode = '';

            // Function to apply voucher
            function applyVoucher() {
                const voucherCode = $('#kode-voucher').val().trim();

                if (!voucherCode) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Silahkan masukkan kode voucher',
                        icon: 'error'
                    });
                    return;
                }

                // Check if same voucher is being applied again
                if (voucherCode === appliedVoucherCode) {
                    Swal.fire({
                        title: 'Info',
                        text: 'Voucher ini sudah diaplikasikan',
                        icon: 'info'
                    });
                    return;
                }

                // Show loading indicator
                Swal.fire({
                    title: 'Memproses',
                    text: 'Memeriksa kode voucher...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request to validate voucher
                $.ajax({
                    url: '{{ route('voucher.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        code: voucherCode
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.status === 'success') {
                            $('#reset-voucher').show();
                            $('input#kode-voucher').prop('readonly', true);
                            // Save the applied voucher and discount percentage
                            voucherDiscount = response.data.persentase;
                            appliedVoucherCode = voucherCode;

                            // Display voucher info
                            $('#voucher-info').html(
                                `<div class="alert alert-success">
                        <strong>Voucher berhasil diaplikasikan!</strong><br>
                        Diskon ${voucherDiscount}% dari ${response.data.nama}
                    </div>`
                            ).show();

                            // Recalculate price with the discount
                            calculateRoute();

                            Swal.fire({
                                title: 'Berhasil',
                                text: `Voucher "${response.data.nama}" berhasil diaplikasikan! Diskon ${voucherDiscount}%`,
                                icon: 'success'
                            });
                        } else {
                            $('#voucher-info').html(
                                `<div class="alert alert-danger">
                        <strong>Voucher tidak valid!</strong><br>
                        ${response.message}
                    </div>`
                            ).show();

                            Swal.fire({
                                title: 'Gagal',
                                text: response.message || 'Voucher tidak valid',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        let errorMessage = 'Terjadi kesalahan saat memeriksa voucher';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('#voucher-info').html(
                            `<div class="alert alert-danger">
                    <strong>Error!</strong><br>
                    ${errorMessage}
                </div>`
                        ).show();

                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            }

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

                // Enable location inputs after map is loaded
                $('#lokasi_awal, #lokasi_akhir').prop('disabled', false);

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

            // Calculate total price with the new tiered pricing model for cars
            function calculatePrice(routeDistance) {
                // Ceiling the distance to get proper tier calculation
                const roundedDistance = Math.ceil(routeDistance);
                let totalPrice = BASE_FEE; // Start with base fee

                // Apply tiered pricing
                if (roundedDistance <= TIER_1_MAX) {
                    // Tier 1: 1-3 km at 6000/km
                    totalPrice += roundedDistance * TIER_1_RATE;
                } else if (roundedDistance <= TIER_2_MAX) {
                    // Tier 2: 4-10 km at 5000/km
                    totalPrice += roundedDistance * TIER_2_RATE;
                } else {
                    // Tier 3: >10 km at 4000/km
                    totalPrice += roundedDistance * TIER_3_RATE;
                }

                return totalPrice;
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

            function calculateRoute() {
                if (!markerAwal || !markerAkhir) return;

                const startPos = markerAwal.getPosition();
                const endPos = markerAkhir.getPosition();

                // Calculate distance from basecamp to pickup point
                const basecampPos = new google.maps.LatLng(BASECAMP_LAT, BASECAMP_LNG);
                const basecampToPickupDistance = calculateDistance(
                    BASECAMP_LAT,
                    BASECAMP_LNG,
                    startPos.lat(),
                    startPos.lng()
                ).toFixed(2);

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

                        // Get rounded distance for display
                        const roundedDistance = Math.ceil(parseFloat(routeDistance));

                        // Calculate price using tiered pricing
                        let totalPrice = calculatePrice(parseFloat(routeDistance));

                        // Determine which rate is applied based on distance
                        let appliedRate;
                        if (roundedDistance <= TIER_1_MAX) {
                            appliedRate = TIER_1_RATE;
                        } else if (roundedDistance <= TIER_2_MAX) {
                            appliedRate = TIER_2_RATE;
                        } else {
                            appliedRate = TIER_3_RATE;
                        }

                        // Apply voucher discount if available
                        let discountAmount = 0;
                        let discountInfo = '';

                        if (voucherDiscount > 0) {
                            discountAmount = Math.round(totalPrice * (voucherDiscount / 100));
                            totalPrice -= discountAmount;
                            discountInfo =
                                `<br>Diskon Voucher (${voucherDiscount}%): -Rp${discountAmount.toLocaleString()}`;
                        }

                        // Send both distances to the server
                        $.ajax({
                            url: `{{ route('taxi.show-pricing') }}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                jarakBaseCampKeTitikJemput: Math.ceil(basecampToPickupDistance),
                                jarakTitikJemputKeTitikTujuan: Math.ceil(routeDistance),
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    let finalPrice = response.harga;

                                    // Apply voucher discount if available
                                    if (voucherDiscount > 0) {
                                        const discountAmount = Math.round(finalPrice * (
                                            voucherDiscount / 100));
                                        finalPrice -= discountAmount;
                                    }
                                    // Format price with thousand separators for Rupiah
                                    const formattedPrice = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(finalPrice);
                                    // Update the route info with the response data
                                    $('#routeInfo').html(
                                        `Jarak Basecamp ke Titik Jemput: ${basecampToPickupDistance} km<br>
                            Jarak Perjalanan: <span id="distance">${roundedDistance}</span> km<br>
                            Estimasi waktu: <span id="duration">${duration}</span> menit<br>
                            ${discountInfo}<br>
                            Harga Total: <h1 id="totalPrice" class="text-success">${formattedPrice}</h1>`
                                    ).show();
                                } else {
                                    // Handle error response
                                    $('#routeInfo').html(
                                        `<div class="alert alert-danger">
                                <strong>Error!</strong><br>
                                Maaf, terjadi kesalahan, silahkan coba lagi.
                            </div>`
                                    ).show();
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghitung harga';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                $('#routeInfo').html(
                                    `<div class="alert alert-danger">
                            <strong>Error!</strong><br>
                            ${errorMessage}
                        </div>`
                                ).show();
                            }
                        });
                    }
                });
            }

            // Add voucher reset functionality
            function resetVoucher() {
                voucherDiscount = 0;
                appliedVoucherCode = '';
                $('input#kode-voucher').prop('readonly', false);
                $('#kode-voucher').val('');
                $('#voucher-info').hide();
                calculateRoute();
            }

            // Add button event listeners
            $('#apply-voucher').click(applyVoucher);

            // Allow enter key to submit voucher
            $('#kode-voucher').keypress(function(e) {
                if (e.which === 13) {
                    applyVoucher();
                    e.preventDefault();
                }
            });

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
                            const lat = parseFloat(position.coords.latitude);
                            const lng = parseFloat(position.coords.longitude);

                            $('#lat_awal').val(lat);
                            $('#lng_awal').val(lng);

                            map.setCenter({
                                lat: lat,
                                lng: lng
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
                            lat: parseFloat(lat),
                            lng: parseFloat(lng)
                        }
                    }, (results, status) => {
                        resolve(
                            status === google.maps.GeocoderStatus.OK &&
                            results &&
                            results.length > 0 &&
                            results[0].geometry &&
                            results[0].geometry.location_type !== 'APPROXIMATE' &&
                            results[0].formatted_address &&
                            !results[0].formatted_address.toLowerCase().includes('undefined')
                        );
                    });
                });
            }

            // Determine cabang based on location
            async function determineCabang(lat, lng) {
                return new Promise((resolve) => {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        location: { 
                            lat: parseFloat(lat), 
                            lng: parseFloat(lng) 
                        }
                    }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            // Get the address components
                            const addressComponents = results[0].address_components;
                            let city = '';
                            
                            // Find the city component
                            for (const component of addressComponents) {
                                if (component.types.includes('locality')) {
                                    city = component.long_name.toLowerCase();
                                    break;
                                }
                            }

                            // Determine cabang based on city
                            let cabangId = null;
                            switch(city) {
                                case 'malang':
                                    cabangId = 1; // Assuming 1 is Malang's cabang ID
                                    break;
                                case 'surabaya':
                                    cabangId = 2; // Assuming 2 is Surabaya's cabang ID
                                    break;
                                // Add more cities as needed
                                default:
                                    cabangId = 1; // Default to Malang or handle as needed
                            }
                            resolve(cabangId);
                        } else {
                            resolve(1); // Default to Malang if geocoding fails
                        }
                    });
                });
            }

            // Modify the order click handler
            $('#order').click(async function() {
                const lat_awal = parseFloat($('#lat_awal').val());
                const lng_awal = parseFloat($('#lng_awal').val());
                const lat_akhir = parseFloat($('#lat_akhir').val());
                const lng_akhir = parseFloat($('#lng_akhir').val());

                // Calculate basecamp to pickup distance
                const basecampPos = new google.maps.LatLng(BASECAMP_LAT, BASECAMP_LNG);
                const basecampToPickupDistance = calculateDistance(
                    BASECAMP_LAT,
                    BASECAMP_LNG,
                    lat_awal,
                    lng_awal
                ).toFixed(2);

                // Validate both locations
                const isStartValid = await validateLocation(lat_awal, lng_awal);
                const isEndValid = await validateLocation(lat_akhir, lng_akhir);

                if (!isStartValid || !isEndValid) {
                    alert('Lokasi tidak valid. Pastikan menggunakan lokasi tepat dari Google Maps.');
                    return;
                }

                // Determine cabang based on pickup location
                const cabangId = await determineCabang(lat_awal, lng_awal);

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
                                _token: '{{ csrf_token() }}',
                                voucher: appliedVoucherCode,
                                jarak: parseFloat($('#distance').text()),
                                jarakBaseCampKeTitikJemput: Math.ceil(basecampToPickupDistance),
                                titik_jemput: $('#lokasi_awal').val(),
                                titik_tujuan: $('#lokasi_akhir').val(),
                                cabang: cabangId
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    // console.log($('#totalPrice').text());

                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: 'Pesanan berhasil dibuat, Anda akan diarahkan ke WhatsApp Admin',
                                        icon: 'success'
                                    }).then(() => {
                                        const message =
                                            `Hii, saya baru saja memesan To Help untuk meminta bantuan\n\n- Mobil\nID Order : ${response.order_id}\nTitik Penjemputan : ${$('#lokasi_awal').val()}\nTitik Pengantaran : ${$('#lokasi_akhir').val()}\nHarga : ${$('#totalPrice').text()}`;
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
            });

            $('#reset-voucher').click(function() {
                resetVoucher();
                $(this).hide();
            });
        });
    </script>
@endpush
