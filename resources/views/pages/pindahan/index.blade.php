@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Jasa Pindahan/Angkut Barang</h2>
                <p class="lead">Layanan angkut barang kami membantu Anda memindahkan barang ke mana saja
                    dengan cepat, aman, dan efisien.</p>
            </div>

            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Tossa</h5>
                            {{-- <h2 class="card-text text-primary mb-4">Rp 4.000/m²</h2> --}}
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Tossa">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pickup</h5>
                            {{-- <h2 class="card-text text-primary mb-4">Rp 5.000/m²</h2> --}}
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Pickup">
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

                const message =
                    `Format Pemesanan\n` +
                    `Jenis pesanan : Pindahan/Angkut Barang\n` +
                    `Alamat ambil / order : \n` +
                    `List barang berat : \n` +
                    `1. ......\n` +
                    `2. ......\n` +
                    `3. ......\n` +
                    `List barang ringan :\n` +
                    `1. ......\n` +
                    `2. ......\n` +
                    `3. ......\n` +
                    `Jenis jasa : ${service}\n` +
                    `Alamat tujuan / kirim : \n` +
                    `Tanggal/Waktu : \n` +
                    `No.hp / wa : \n` +
                    `Atas nama : `;

                window.open(
                    `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                    '_blank');
            });
        });
    </script>
@endpush
