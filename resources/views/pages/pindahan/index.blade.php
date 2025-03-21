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
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/tossa.png') }}" alt="" width="200px">
                                <h5 class="card-title fw-bold mb-0">Tossa</h5>
                            </div>
                            <div class="text-center mb-4">
                                <h3 class="text-success mb-0">Start from 50rb</h3>
                            </div>
                            <div class="border-top border-bottom py-3 mb-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-users text-muted me-2"></i>
                                    <p class="mb-0">Tambahan kuli: 30rb/orang</p>
                                </div>
                            </div>
                            <a href="#" class="btn btn-success btn-lg w-100 order-btn" data-service="Tossa"
                                data-price="50000">
                                <i class="fab fa-whatsapp me-2"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/pickup.png') }}" alt="" width="200px">
                                <h5 class="card-title fw-bold mb-0">Pickup</h5>
                            </div>
                            <div class="text-center mb-4">
                                <h3 class="text-success mb-0">Start from 100rb</h3>
                            </div>
                            <div class="border-top border-bottom py-3 mb-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-users text-muted me-2"></i>
                                    <p class="mb-0">Tambahan kuli: 30rb/orang</p>
                                </div>
                            </div>
                            <a href="#" class="btn btn-success btn-lg w-100 order-btn" data-service="Pickup"
                                data-price="100000">
                                <i class="fab fa-whatsapp me-2"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                let service = $(this).data('service');
                let price = $(this).data('price');

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
                            url: `{{ route('pindahan.pesan') }}`,
                            method: 'POST',
                            data: {
                                // csrf
                                _token: '{{ csrf_token() }}',
                                jasa: service,
                                total_harga: price,
                                alamat: result.value
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: 'Pesanan berhasil dibuat, Anda akan diarahkan ke WhatsApp Admin',
                                        icon: 'success'
                                    }).then(() => {
                                        const message =
                                            `Format Pemesanan\n` +
                                            `ID Order : ${response.order_id}\n` +
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
                                            `Perlu kuli tambahan? (ya/tidak) : \n` +
                                            `Jika ya, berapa orang : \n` +
                                            `Alamat tujuan / kirim : \n` +
                                            `Tanggal/Waktu : \n` +
                                            `No.hp / wa : \n` +
                                            `Atas nama : `;

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
                // const message =
                //     `Format Pemesanan\n` +
                //     `Jenis pesanan : Pindahan/Angkut Barang\n` +
                //     `Alamat ambil / order : \n` +
                //     `List barang berat : \n` +
                //     `1. ......\n` +
                //     `2. ......\n` +
                //     `3. ......\n` +
                //     `List barang ringan :\n` +
                //     `1. ......\n` +
                //     `2. ......\n` +
                //     `3. ......\n` +
                //     `Jenis jasa : ${service}\n` +
                //     `Perlu kuli tambahan? (ya/tidak) : \n` +
                //     `Jika ya, berapa orang : \n` +
                //     `Alamat tujuan / kirim : \n` +
                //     `Tanggal/Waktu : \n` +
                //     `No.hp / wa : \n` +
                //     `Atas nama : `;

                // window.open(
                //     `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                //     '_blank'
                // );
            });
        });
    </script>
@endpush
