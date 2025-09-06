@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Travel</h2>
                <p class="lead">Layanan rental mobil dan motor harian profesional untuk memastikan aktivitas Anda berjalan
                    lancar dan nyaman.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Rental Mobil</h5>
                            <h2 class="card-text text-primary mb-3">Start from Rp 250.000*</h2>
                            <p class="card-text mb-4">
                                Varian Unit:<br>
                                1. Hiace Comuter 14 seat<br>
                                2. Hiace Premio 14 Seat<br>
                                3. Avanza<br>
                                4. Xenia<br>
                                5. Innova Grand / Barong<br>
                                6. Reborn Diesel<br>
                                NB: Tambahan driver Mobil (200rb - 350rb)
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Rental Mobil"
                                data-price="250.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Rental Motor</h5>
                            <h2 class="card-text text-primary mb-3">Only Rp 70.000*</h2>
                            <p class="card-text mb-4">
                                Varian Unit:<br>
                                1. Beat<br>
                                2. Vario<br>
                                NB: Tambahan driver Motor (100rb - 150rb)<br><br><br><br><br>
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Rental Motor"
                                data-price="250.000">
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
                const jenisKendaraan = service.replace('Rental ', '');

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
                            url: `{{ route('travel.pesan') }}`,
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
                                            `Hii kak, saya ingin menyewa kendaraan\n\n` +
                                            `ID Order : ${response.order_id}\n` +
                                            `Jenis Kendaraan : ${jenisKendaraan}\n` +
                                            `Nama Unit : \n` +
                                            `Tambahan Driver : ( ya / tidak)\n` +
                                            `Di ambil / diantar : \n` +
                                            `Alamat tujuan : (apabila diantar)\n` +
                                            `Waktu Ambil : \n` +
                                            `Waktu kembali : \n` +
                                            `Nama : \n` +
                                            `Nomor WhatsApp : \n\n` +
                                            `*${jenisKendaraan === 'Mobil' ? 'Tolong siapkan KTP asli + Sepeda motor dan STNK sebagai jaminan 🙏🏻' : 'Tolong siapkan KTP asli sebagai jaminan 🙏🏻'}*`;

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
