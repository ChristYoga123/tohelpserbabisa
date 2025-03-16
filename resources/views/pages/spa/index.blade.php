@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">SPA</h2>
                <p class="lead">Layanan SPA profesional untuk memastikan Anda merasa rileks dan segar kembali.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">To Help Escape (120min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 200.000</h2>
                            <p class="card-text mb-4">
                                Include:<br>
                                - Body massage<br>
                                - Body scrub<br>
                                - Body mask<br>
                                - head massage
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="To Help Escape (120min)"
                                data-price="200.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Heavenly Harmony (90min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 165.000</h2>
                            <p class="card-text mb-4">
                                Include:<br>
                                - Body massage<br>
                                - Hot stone<br><br><br>
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn"
                                data-service="Heavenly Harmony (90min)" data-price="165.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Blissful Glow (90min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 120.000</h2>
                            <p class="card-text mb-4">
                                Include:<br>
                                - Body massage<br>
                                - Kerokan<br><br><br>
                            </p>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Blissful Glow (90min)"
                                data-price="120.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Body Massage (60min) </h5>
                            <h2 class="card-text text-primary mb-3">Rp 100.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Body Massage (60min)"
                                data-price="100.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Body Massage (90min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 140.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Body Massage (90min)"
                                data-price="140.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"> Body Massage (120min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 180.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Body Massage (120min)"
                                data-price="180.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Head Massage (20min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 35.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Head Massage (20min)"
                                data-price="35.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Rosemary Scalp Revive</h5>
                            <h2 class="card-text text-primary mb-3">Rp 45.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Rosemary Scalp Revive"
                                data-price="45.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Body Scrub (30min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 40.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Body Scrub (30min)"
                                data-price="40.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Body Scrub + Body Mask (60min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 80.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn"
                                data-service="Body Scrub + Body Mask (60min)" data-price="80.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Totok Wajah + Mask (60min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 50.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn"
                                data-service="Totok Wajah + Mask (60min)" data-price="50.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Refleksi (20min)</h5>
                            <h2 class="card-text text-primary mb-3">Rp 35.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Refleksi (20min)"
                                data-price="35.000">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kerokan</h5>
                            <h2 class="card-text text-primary mb-3">Rp 20.000</h2>
                            <a href="#" class="btn btn-success w-100 order-btn" data-service="Kerokan"
                                data-price="20.000">
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
                        if (price) {
                            data.total_harga = parseInt(price.replace(/\D/g,
                                ''));
                        }

                        $.ajax({
                            url: `{{ route('spa.pesan') }}`,
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
                                            `Jenis Jasa : SPA\n` +
                                            `Treatment: ${service}\n` +
                                            `Nama : \n` +
                                            `No. HP : \n` +
                                            `Gender : \n` +
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
            });
        });
    </script>
@endpush
