@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Bersih-bersih Rumah</h2>
                <p class="lead">Layanan pembersihan rumah profesional untuk memastikan lingkungan Anda tetap bersih dan
                    nyaman.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ruang Tamu</h5>
                            <h2 class="card-text text-primary mb-4">Rp 4.000/m²</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Ruang Tamu"
                                data-price="4.000/m²">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Tidur</h5>
                            <h2 class="card-text text-primary mb-4">Rp 5.000/m²</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Kamar Tidur"
                                data-price="5.000/m²">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Dapur</h5>
                            <h2 class="card-text text-primary mb-4">Rp 5.000/m²</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Dapur"
                                data-price="5.000/m²">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Halaman</h5>
                            <h2 class="card-text text-primary mb-4">Rp 4.000/m²</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Halaman"
                                data-price="4.000/m²">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Mandi (Kecil)</h5>
                            <h2 class="card-text text-primary mb-4">Rp 35.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Kamar Mandi Kecil"
                                data-price="35.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Mandi (Besar)</h5>
                            <h2 class="card-text text-primary mb-4">Rp 50.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Kamar Mandi Besar"
                                data-price="50.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.order-btn').click(function(e) {
                e.preventDefault();
                const service = $(this).data('service');
                const price = $(this).data('price');

                const message =
                    `Hello Minhelp, saya ingin meminta bantuan Cleaning Service dan saya sudah membaca Price List di Website\n\nJasa: ${service}\nHarga: Rp ${price}`;

                window.open(
                    `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                    '_blank');
            });
        });
    </script>
@endpush
