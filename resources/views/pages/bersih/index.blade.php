@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Bersih-bersih</h2>
                <p class="lead">Layanan pembersihan profesional untuk memastikan lingkungan Anda tetap bersih dan
                    nyaman.</p>
            </div>

            <div class="row g-4">
                <!-- Rumah Subsidi Package -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Rumah Subsidi</h5>
                            <h2 class="card-text text-primary mb-3">Rp 170.000</h2>
                            <p class="card-text mb-4">
                                Include:<br>
                                - 2 kamar tidur<br>
                                - 1 kamar mandi kecil<br>
                                - 1 dapur<br>
                                - Ruang tamu<br>
                                - Halaman depan
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Rumah Subsidi"
                                data-price="170.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Rumah Komersil Package -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Rumah Komersil</h5>
                            <h2 class="card-text text-primary mb-3">Rp 300.000</h2>
                            <p class="card-text mb-4">
                                Include:<br>
                                - 3 kamar tidur<br>
                                - 1 kamar mandi besar<br>
                                - 1 dapur besar<br>
                                - Ruang tengah<br>
                                - Ruang keluarga<br>
                                - Halaman depan
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Rumah Komersil"
                                data-price="300.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

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
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Tandon</h5>
                            <h2 class="card-text text-primary mb-3">Rp 50.000 - Rp 175.000</h2>
                            <p class="card-text mb-4">
                                Price List:<br>
                                - 225L - 720L (start from 50k)<br>
                                - 840L - 1.200L (start 75k)<br>
                                - 2.200L - 3.300 (start from 100k)<br>
                                - 5.700L (125k)<br>
                                - 10.500L (175k)<br>
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Tandon"
                                data-price="170.000">
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
                // console.log(parseInt(price.replace(/\D/g, '')));

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
                            alamat: result.value
                        };

                        if (service !== 'Tandon') {
                            data.total_harga = parseInt(price.replace(/\D/g, ''));
                        }

                        $.ajax({
                            url: `{{ route('bersih.pesan') }}`,
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
                                            `Hello Minhelp, saya ingin meminta bantuan Cleaning Service dan saya sudah membaca Price List di Website\n\n` +
                                            `Harap Di Isi, Format Order Berikut\n` +
                                            `ID Order : ${response.order_id}\n` +
                                            `Jasa : ${service}\n` +
                                            `${service === 'Tandon' ? '' : `Harga : Rp ${price}\n`}` +
                                            `Tanggal Pengerjaan : \n` +
                                            `Alamat : `;

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

                // $.ajax({
                //     url: `{{ route('bersih.pesan') }}`,
                //     method: 'POST',
                //     data: {
                //         // csrf
                //         _token: '{{ csrf_token() }}',
                //         jasa: service,
                //         price: parseInt(price.replace(/\D/g, ''))
                //     },
                //     success: function(response) {
                //         if (response.status)
                //     }
                // })

                // const message =
                //     `Hello Minhelp, saya ingin meminta bantuan Cleaning Service dan saya sudah membaca Price List di Website\n\n` +
                //     `Harap Di Isi, Format Order Berikut\n` +
                //     `Jasa : ${service}\n` +
                //     `Harga : Rp ${price}\n` +
                //     `Tanggal Pengerjaan : \n` +
                //     `Alamat : `;

                // window.open(
                //     `https://api.whatsapp.com/send?phone=6285695908981&text=${encodeURIComponent(message)}`,
                //     '_blank');
            });
        });
    </script>
@endpush
