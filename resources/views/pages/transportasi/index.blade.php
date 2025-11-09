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
                <div class="m-lg-5 p-lg-5 m-4 p-4">
                    <h6 class="text-white"><span class="text-primary">Transportasi</span>(Ojek)</h6>
                    <h2 class="display-4 fw-bold my-4 text-white">Perjalanan Nyaman, Harga Bersahabat</h2>
                    <a class="btn btn-light btn-bg btn-slide hover-slide-right mt-4" href="#quote">
                        <span>Silahkan Pesan</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-container">
                    <img class="img-fluid hero-image" src="{{ asset('images/hero-ojek.jpg') }}" alt="img">
                </div>
            </div>
        </div>
    </section>

    <!-- Map/Order Section -->
    <section class="padding-small" id="quote">
        <div class="container text-center">
            <h3 class="display-6 fw-semibold mb-4">Tentukan Lokasi</h3>
            <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>

            <button class="btn btn-primary mb-3" id="useMyLocation">
                <i class="fas fa-location-arrow"></i> Gunakan Lokasi Saya
            </button>

            <div class="alert alert-info mb-3" id="routeInfo" style="display: none;"></div>

            <form class="contact-form row mt-5" id="locationForm">
                <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                    <input class="form-control w-100 rounded-0 py-2 ps-3" id="lokasi_awal" name="lokasi_awal" type="text"
                        placeholder="Mendapatkan lokasi Anda..." required disabled>
                    <input id="lat_awal" name="lat_awal" type="hidden">
                    <input id="lng_awal" name="lng_awal" type="hidden">
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
                    <input class="form-control w-100 rounded-0 py-2 ps-3" id="lokasi_akhir" name="lokasi_akhir"
                        type="text" placeholder="Masukkan Tujuan*" required disabled>
                    <input id="lat_akhir" name="lat_akhir" type="hidden">
                    <input id="lng_akhir" name="lng_akhir" type="hidden">
                </div>

                <!-- Payment method dropdown (tengahkan) -->
                <div class="col-12 mb-3 max-w-20 text-center">
                    <label class="form-label fw-semibold d-block mb-2" for="metode_pembayaran">Metode Pembayaran</label>

                    <select class="form-select rounded-0 d-inline-block mx-auto py-2 ps-3" id="metode_pembayaran">
                        <option value="Cash">Cash</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer">Transfer</option>
                    </select>

                </div>
            </form>

            <!-- Voucher Section -->
            <div class="mb-3 mt-4">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="input-group">
                            <input class="form-control rounded-0 py-2 ps-3" id="kode-voucher" type="text"
                                aria-label="Kode voucher" placeholder="Masukkan kode voucher">
                            <button class="btn btn-primary" id="apply-voucher" type="button">
                                <i class="fas fa-tag"></i> Apply Voucher
                            </button>
                            <button class="btn btn-outline-secondary" id="reset-voucher" type="button"
                                style="display: none;">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        </div>
                        <div class="mt-2 text-start" id="voucher-info" style="display: none;"></div>
                    </div>

                </div>
                <div class="col-md-12 mt-4">
                    <div class="alert alert-info border">
                            <div>Setiap pemberhentian di setiap rute yang sama ditambahkan <strong>Rp2.000</strong> per
                                lokasi.</div>
                            <div>Setiap menunggu akan dikenakan <strong>Rp5.000 per 30 menit</strong>.</div>
                        <div class="mt-1"><em>Biaya tambahan akan dihitung saat proses layanan atau sesuai kebijakan.</em>
                        </div>
                    </div>
                </div>

                <a class="btn btn-success mt-3" id="order">
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
            const BASECAMP_LAT = {{ env('BASECAMP_LAT') }};
            const BASECAMP_LNG = {{ env('BASECAMP_LONG') }};
            const BASE_FEE = {{ $tarifDasar->harga ?? 0 }}; // Base fee fallback

            // New pricing model
            const RATE_PER_KM = 2000; // 2rb/km for motorcycle
            const MINIMUM_ORDER_PRICE = 7000; // Minimum order price is 7rb

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

                            // $('#totalPrice').html(parseInt($('#totalPrice').text().replace(/\D/g, '') -
                            //     ($(
                            //         '#totalPrice').text().replace(/\D/g, '')) * (
                            //         voucherDiscount / 100)));

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

            // Calculate total price with the new flat rate pricing model
            function calculatePrice(routeDistance) {
                // Ceiling the distance to get proper calculation
                const roundedDistance = Math.ceil(routeDistance);

                // Calculate base price (distance × rate per km)
                let calculatedPrice = roundedDistance * RATE_PER_KM;

                // Apply minimum order price if the calculated price is lower
                let totalPrice = Math.max(calculatedPrice, MINIMUM_ORDER_PRICE);

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

                // Calculate distance from basecamp to destination point
                const basecampToDestinationDistance = calculateDistance(
                    BASECAMP_LAT,
                    BASECAMP_LNG,
                    endPos.lat(),
                    endPos.lng()
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

                        // Calculate return trip distance (destination to pickup) in background
                        const returnRequest = {
                            origin: endPos,
                            destination: startPos,
                            travelMode: 'DRIVING'
                        };

                        directionsService.route(returnRequest, function(returnResult, returnStatus) {
                            let returnDistance = routeDistance; // fallback to same distance
                            
                            if (returnStatus === 'OK') {
                                returnDistance = (returnResult.routes[0].legs[0].distance.value / 1000).toFixed(2);
                            }

                            // Get rounded distance for display
                            const roundedDistance = Math.ceil(parseFloat(routeDistance));

                            // Calculate price using new flat rate pricing
                            let calculatedPrice = roundedDistance * RATE_PER_KM;
                            let totalPrice = Math.max(calculatedPrice, MINIMUM_ORDER_PRICE);
                            let priceInfo = '';

                            // Create appropriate price explanation based on whether minimum price is applied
                            if (calculatedPrice < MINIMUM_ORDER_PRICE) {
                                priceInfo = `Tarif: Rp${MINIMUM_ORDER_PRICE.toLocaleString()} (Tarif minimum)`;
                            } else {
                                priceInfo =
                                    `Tarif: Rp${totalPrice.toLocaleString()} (${roundedDistance} km × Rp${RATE_PER_KM.toLocaleString()}/km)`;
                            }

                            // Voucher discount info for display (calculation will be done by backend)
                            let discountInfo = '';
                            if (voucherDiscount > 0) {
                                discountInfo = `<br>Diskon Voucher: ${voucherDiscount}%`;
                            }

                            // Send both distances to the server including calculated return distance
                            $.ajax({
                                url: `{{ route('ojek.show-pricing') }}`,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    jarakBaseCampKeTitikJemput: Math.ceil(basecampToPickupDistance),
                                    jarakTitikJemputKeTitikTujuan: Math.ceil(routeDistance),
                                    jarakBaseCampKeTitikTujuan: Math.ceil(basecampToDestinationDistance),
                                    jarakTitikTujuanKeTitikJemput: Math.ceil(returnDistance),
                                    discount: appliedVoucherCode || null,
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                    // Backend already calculated final price including discount
                                        let finalPrice = response.harga;

                                    // Format price with thousand separators for Rupiah
                                    const formattedPrice = new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            }).format(finalPrice);

                                    // Update the route info with the response data
                                        $('#routeInfo').html(
                                            `Jarak Driver ke Titik Jemput: ${basecampToPickupDistance} km<br>
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
                        }); // Close return distance calculation callback
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

                // Calculate basecamp to pickup distance
                const basecampToPickupDistance = calculateDistance(
                    BASECAMP_LAT,
                    BASECAMP_LNG,
                    lat_awal,
                    lng_awal
                ).toFixed(2);

                // Calculate basecamp to destination distance
                const basecampToDestinationDistance = calculateDistance(
                    BASECAMP_LAT,
                    BASECAMP_LNG,
                    lat_akhir,
                    lng_akhir
                ).toFixed(2);

                // Validate both locations
                const isStartValid = await validateLocation(lat_awal, lng_awal);
                const isEndValid = await validateLocation(lat_akhir, lng_akhir);

                if (!isStartValid || !isEndValid) {
                    alert('Lokasi tidak valid. Pastikan menggunakan lokasi tepat dari Google Maps.');
                    return;
                }

                // Calculate return distance for accurate pricing
                const startPos = new google.maps.LatLng(lat_awal, lng_awal);
                const endPos = new google.maps.LatLng(lat_akhir, lng_akhir);

                const returnRequest = {
                    origin: endPos,
                    destination: startPos,
                    travelMode: 'DRIVING'
                };

                directionsService.route(returnRequest, function(returnResult, returnStatus) {
                    let returnDistance = parseFloat($('#distance').text()); // fallback to forward distance
                    
                    if (returnStatus === 'OK') {
                        returnDistance = (returnResult.routes[0].legs[0].distance.value / 1000)
                            .toFixed(2);
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
                            // metode pembayaran TIDAK dikirim ke API.
                            // It will only be included in the WhatsApp message.
                            $.ajax({
                                url: `{{ route('ojek.pesan') }}`,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                        total_harga: parseInt(price.replace(/\D/g, '')),
                                        jarak: parseFloat($('#distance').text()),
                                        jarakBaseCampKeTitikJemput: Math.ceil(basecampToPickupDistance),
                                        jarakBaseCampKeTitikTujuan: Math.ceil(basecampToDestinationDistance),
                                        jarakTitikTujuanKeTitikJemput: Math.ceil(returnDistance),
                                        voucher: appliedVoucherCode,
                                    titik_jemput: $('#lokasi_awal').val(),
                                    titik_tujuan: $('#lokasi_akhir').val(),
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            title: 'Berhasil',
                                            text: 'Pesanan berhasil dibuat, Anda akan diarahkan ke WhatsApp Admin',
                                            icon: 'success'
                                        }).then(() => {
                                            // Build WhatsApp message and include payment method information
                                            const paymentText =
                                                `Metode Pembayaran: ${paymentMethod}`;
                                            const message =
                                                `Hii, saya baru saja memesan To Help untuk meminta bantuan

- Layanan: Ojek
ID Order : ${response.order_id}
Titik Penjemputan : ${$('#lokasi_awal').val()}
Titik Pengantaran : ${$('#lokasi_akhir').val()}
Harga : ${$('#totalPrice').text()}
${paymentText}`

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
                }); // Close return distance calculation callback
            });

            $('#reset-voucher').click(function() {
                resetVoucher();
                $(this).hide();
            });
        });
    </script>
@endpush
