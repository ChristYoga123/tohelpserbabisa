@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Jasa Sesuai Permintaan</h2>
                <p class="lead">Dari membantu belanja, mengurus keperluan administrasi, hingga
                    tugas-tugas khusus lainnya, kami siap melayani Anda.</p>
            </div>

            <!-- Services Section -->
            @php
                /*
                Jastip 
1. Jastip Makanan
2. Jastim Minuman
3. Jastip Barang

Daily Activity
1. Rawat Peliharaan
2. Pasang Gas/Galon
3. Jaga Anak

Jasa Nemenin
1. Teman Ngopi
2. Teman Nonton
3. Teman Curhat
4. Teman Acara (Kondangan, Pesta, Wisuda, dll)

Laundry
1. Antar Cuci Sepeda
2. Antar Cuci Mobil
3. Antar Cuci Baju 

All service
1. Antar Service Sepeda
2. Antar Service Mobil

Travel
1. Driver
2. Rental Motor
3. Rental Mobil

Editing
1. Edit Foto/Video
2. Fotographer
3. Videographer

Bantuan Online
1. SleepCall
2. Stalker
3. Joki Game
4. Buzzer
                */
                $services = [
                    'Jastip' => [
                        ['name' => 'Jastip Makanan', 'icon' => 'fas fa-utensils'],
                        ['name' => 'Jastip Minuman', 'icon' => 'fas fa-coffee'],
                        ['name' => 'Jastip Barang', 'icon' => 'fas fa-box'],
                    ],
                    'Daily Activity' => [
                        ['name' => 'Rawat Peliharaan', 'icon' => 'fas fa-paw'],
                        ['name' => 'Pasang Gas/Galon', 'icon' => 'fas fa-gas-pump'],
                        ['name' => 'Jaga Anak', 'icon' => 'fas fa-baby'],
                    ],
                    'Jasa Nemenin' => [
                        ['name' => 'Teman Ngopi', 'icon' => 'fas fa-coffee'],
                        ['name' => 'Teman Nonton', 'icon' => 'fas fa-film'],
                        ['name' => 'Teman Curhat', 'icon' => 'fas fa-comments'],
                        ['name' => 'Teman Acara', 'icon' => 'fas fa-glass-cheers'],
                    ],
                    'Laundry' => [
                        ['name' => 'Antar Cuci Sepeda', 'icon' => 'fas fa-bicycle'],
                        ['name' => 'Antar Cuci Mobil', 'icon' => 'fas fa-car'],
                        ['name' => 'Antar Cuci Baju', 'icon' => 'fas fa-tshirt'],
                    ],
                    'All service' => [
                        ['name' => 'Antar Service Sepeda', 'icon' => 'fas fa-bicycle'],
                        ['name' => 'Antar Service Mobil', 'icon' => 'fas fa-car'],
                    ],
                    'Travel' => [
                        ['name' => 'Driver', 'icon' => 'fas fa-car'],
                        ['name' => 'Rental Motor', 'icon' => 'fas fa-motorcycle'],
                        ['name' => 'Rental Mobil', 'icon' => 'fas fa-car'],
                    ],
                    'Editing' => [
                        ['name' => 'Edit Foto/Video', 'icon' => 'fas fa-camera'],
                        ['name' => 'Fotographer', 'icon' => 'fas fa-camera'],
                        ['name' => 'Videographer', 'icon' => 'fas fa-video'],
                    ],
                    'Bantuan Online' => [
                        ['name' => 'SleepCall', 'icon' => 'fas fa-bed'],
                        ['name' => 'Stalker', 'icon' => 'fas fa-binoculars'],
                        ['name' => 'Joki Game', 'icon' => 'fas fa-gamepad'],
                        ['name' => 'Buzzer', 'icon' => 'fas fa-bullhorn'],
                    ],
                ];
            @endphp

            @foreach ($services as $category => $items)
                <div class="mb-5">
                    <h3 class="text-center mb-4">{{ $category }}</h3>
                    <div class="row justify-content-center g-4">
                        @foreach ($items as $service)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-shadow transition">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-4">
                                            <i class="{{ $service['icon'] }} fa-3x mb-3 text-success"></i>
                                            <h5 class="card-title fw-bold mb-0">{{ $service['name'] }}</h5>
                                        </div>
                                        <a href="#" class="btn btn-success btn-lg w-100 order-btn"
                                            data-service="{{ $service['name'] }}">
                                            <i class="fab fa-whatsapp me-2"></i> Hubungi Kami
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .transition {
            transition: all 0.3s ease;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.order-btn').click(function(e) {
                e.preventDefault();
                const service = $(this).data('service');

                const message =
                    `Format Pemesanan\n` +
                    `Jenis layanan: ${service}\n` +
                    `Nama: \n` +
                    `No. HP/WA: \n` +
                    `Alamat: \n` +
                    `Tanggal/Waktu: \n` +
                    `Detail pesanan: \n` +
                    `Catatan tambahan: `;

                window.open(
                    `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                    '_blank'
                );
            });
        });
    </script>
@endpush
