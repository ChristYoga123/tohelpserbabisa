@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Daily Activity</h2>
                <p class="lead">Layanan harian profesional untuk memastikan aktivitas Anda berjalan lancar dan nyaman.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Paket Durasi</h5>
                            <h2 class="card-text text-primary mb-3">Start from Rp 15.000*</h2>
                            <p class="card-text mb-4">
                                Ketentuan:<br>
                                - Rp 15.000/jam<br>
                                - Transport 2rb/km (jika menggunakan kendaraan help man untuk menuju lokasi orderan)
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Paket Durasi"
                                data-price="15.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Paket Berat & Ringan</h5>
                            <h2 class="card-text text-primary mb-3">Start from Rp 5.000*</h2>
                            <p class="card-text mb-4">
                                Ketentuan:<br>
                                - Transport 2rb/km (jika menggunakan kendaraan help man untuk menuju lokasi orderan)
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Paket Berat & Ringan"
                                data-price="5.000">
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

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Apakah anda yakin ingin memesan jasa ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, pesan!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = {
                            _token: '{{ csrf_token() }}',
                            jasa: service,
                        };

                        $.ajax({
                            url: `{{ route('daily.pesan') }}`,
                            method: 'POST',
                            data: data,
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: 'Pesanan berhasil dibuat, Anda akan diarahkan ke WhatsApp Admin',
                                        icon: 'success'
                                    }).then(() => {
                                        const message =
                                            `Hii kak, saya ingin meminta bantuan To Help\n\n` +
                                            `ID Order : ${response.order_id}\n` +
                                            `Jenis Jasa : Daily Activity\n` +
                                            `Paket : ${service}\n` +
                                            `Masalah Yang Sedang Dihadapi : \n` +
                                            `Bantuan yang di inginkan : \n` +
                                            `Hari/tanggal Bantuan : \n` +
                                            `Waktu : \n` +
                                            `Lokasi Bantuan : \n` +
                                            `Nama : \n` +
                                            `Nomor WhatsApp : \n` +
                                            `Payment (cash/TF) : `;

                                        window.open(
                                            `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                                            '_blank');
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
        });
    </script>
@endpush
