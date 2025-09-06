@extends('layouts.app')

@section('content')
    <section class="padding-small">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Jasa Nemenin</h2>
                <p class="lead">Layanan menemani aktivitas Anda seperti ngopi, nonton, atau kegiatan lainnya dengan tarif
                    terjangkau dan nyaman.</p>
            </div>

            <div class="row g-4">
                <div class="accordion mb-4" id="jasaNemeninFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                🗣️ Min, kalau nemenin ngopi / nonton berapa harga nya?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#jasaNemeninFaq">
                            <div class="accordion-body">
                                Untuk nemenin ngopi tarifnya 15k/ jam kaa, dan akan ditambah biaya transport 2k/km jika
                                jarak lokasi helpman ke lokasi ngopi lebih dari 3km
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                                🗣️ Kalo helpmannya jemput aku dulu gimana min, lalu mampir2 beli sesuatu?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#jasaNemeninFaq">
                            <div class="accordion-body">
                                Untuk hitungan tarif akan dihitung sesuai jarak tempuh 2k/km, namun tarif tersebut akan
                                batal jika besok memakai kendaraan dari customer. Lalu ditambah dengan jasa durasi 15k/jam,
                                terhitung semenjak berhenti di tempat tertentu
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                                🗣️ Kalo mampirnya ga sampek sejam gimana min?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#jasaNemeninFaq">
                            <div class="accordion-body">
                                Kalo ga sampek sejam akan dikalkulasikan dengan pemberhentian selanjutnya. Tapi kalo
                                pemberhentiannya cuman 1 dan ga sampek 1 jam maka ada jasa durasi dibawah itu sebesar 5k
                                untuk 5 - 30 menit, dan 10k untuk 45 menit. Dan total durasi pemberhentian akan
                                dikalkulasikan di akhir
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="#" class="btn btn-success w-100 order-btn" data-service="Nemenin" data-price="15.000">
                        <i class="fab fa-whatsapp"></i> Pesan Sekarang
                    </a>
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
                            url: `{{ route('nemenin.pesan') }}`,
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
                                            `Jenis Jasa : Nemenin\n` +
                                            `Permintaan (pilih salah satu) : ngopi/nonton/night ride/yang lain…\n` +
                                            `Hari/tanggal : \n` +
                                            `Waktu : \n` +
                                            `Nama : \n` +
                                            `Pilih Talent : \n` +
                                            `Nomor WhatsApp : \n` +
                                            `Payment (Cash/TF) : \n\n` +
                                            `Dijemput / Menjemput : (tulis alamat kalian apabila ingin dijemput)\n` +
                                            `Noted : (untuk cek talent, bisa kunjungi website di menu bagian profile. Bingung? Tanya admin)`;

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
