@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Joki Tugas</h2>
                <p class="lead">Layanan harian profesional untuk memastikan aktivitas Anda berjalan lancar dan nyaman.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Makalah</h5>
                            <h2 class="card-text text-primary mb-3">Rp 4.000-<br>Rp 7.000/lembar</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Makalah">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ppt</h5>
                            <h2 class="card-text text-primary mb-3">Rp 3.000/point<br><br></h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Ppt" data-price="3.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Esai/Soal</h5>
                            <h2 class="card-text text-primary mb-3">Start from<br>Rp 5.000/soal</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Esai/Soal">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Matematika</h5>
                            <h2 class="card-text text-primary mb-3">Start from<br>Rp 8.000/soal</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Matematika">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Judul Skripsi</h5>
                            <h2 class="card-text text-primary mb-3">Rp 50.000<br><br></h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Judul Skripsi"
                                data-price="50.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Proposal</h5>
                            <h2 class="card-text text-primary mb-3">Paket Sempro<br>Rp 750.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Proposal"
                                data-price="750.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Skripsi</h5>
                            <h2 class="card-text text-primary mb-3">Bab 4<br>Rp 800.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Skripsi Bab 4"
                                data-price="800.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Skripsi</h5>
                            <h2 class="card-text text-primary mb-3">Bab 5<br>Rp 170.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Skripsi Bab 5"
                                data-price="170.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pharafrase</h5>
                            <h2 class="card-text text-primary mb-3">Start from Rp 5.000*</h2>
                            <p class="card-text mb-4">
                                Ketentuan harga:<br>
                                - &lt;25 halaman : 5k/%<br>
                                - 26-50 hal : 7k/%<br>
                                - 51-100 hal : 10k/%<br>
                                - &gt;100 hal : 12k/%
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Pharafrase">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-info border">
                        <strong>NB:</strong> apabila ada tugas diluar price list, bisa ditanyakan kepada admin.
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
                        if (price) {
                            data.total_harga = parseInt(price.replace(/\D/g,
                                ''));
                        }

                        $.ajax({
                            url: `{{ route('joki-tugas.pesan') }}`,
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
                                            `Jenis Jasa : Joki Tugas\n` +
                                            `Tipe Jasa : ${service}\n` +
                                            `Deadline : \n` +
                                            `Nama : \n` +
                                            `Nomor WhatsApp : `;

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
