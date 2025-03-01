@extends('layouts.app')
@push('styles')
    <style>
        .star-rating {
            font-size: 24px;
            cursor: pointer;
            direction: rtl;
            /* Reverse the direction */
            text-align: center;
        }

        .star-rating i {
            color: #ccc;
            transition: color 0.2s;
            display: inline-block;
            padding: 0 5px;
        }

        /* Change hover behavior to only affect previous stars */
        .star-rating i:hover,
        .star-rating i:hover~i {
            color: #ffd700;
        }

        .star-rating i.active {
            color: #ffd700;
        }
    </style>
@endpush
@section('content')
    <section id="billboard">
        <div class="row align-items-center g-0 bg-secondary">
            <div class="col-lg-6">
                <div class="m-4 p-4 m-lg-5 p-lg-5">
                    <h6 class="text-white"><span class="text-primary">#</span>1 Pelayanan Terbaik</h6>
                    <h2 class="display-4 fw-bold text-white my-4">Bantuan Cepat, Masalah Teratasi, Sesuai Kebutuhan
                    </h2>
                    <ul class="info d-flex flex-wrap align-items-center list-unstyled">
                        <li class="fw-medium text-white d-flex align-items-center me-4">
                            <svg class="text-primary me-1" width="20" height="20">
                                <use xlink:href="#check-circle"></use>
                            </svg> Ngojek
                        </li>
                        <li class="fw-medium text-white d-flex align-items-center me-4">
                            <svg class="text-primary me-1" width="20" height="20">
                                <use xlink:href="#check-circle"></use>
                            </svg> Home Service
                        </li>
                        <li class="fw-medium text-white d-flex align-items-center me-4">
                            <svg class="text-primary me-1" width="20" height="20">
                                <use xlink:href="#check-circle"></use>
                            </svg> Pelayanan Kustom
                        </li>
                    </ul>
                    <a href="#services" class="btn btn-light btn-bg btn-slide hover-slide-right mt-4">
                        <span>Pesan Sekarang</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/hero.png') }}" alt="img" class="img-fluid">
            </div>
        </div>
    </section>

    <section id="about-us" class="padding-small">
        <div class="container">
            <div class="row g-md-5 align-items-center">
                <div class="col-lg-5">
                    <div class="imageblock position-relative">
                        <img class="img-fluid" src="{{ asset('images/about.jpg') }}" alt="img">
                        {{-- <div class="video-player position-absolute top-50 start-50 translate-middle">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#myModal"
                                class="play-btn position-relative">
                                <svg class="play-icon text-primary my-3" width="80" height="80">
                                    <use xlink:href="#play-button"></use>
                                </svg>
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-7 mt-5">
                    <h6><span class="text-primary">|</span>Tentang Kami</h6>
                    <h3 class="display-6 fw-semibold mb-4">Kami Tenaga Ahli Profesional</h3>
                    <p>Di tengah kesibukan dan kompleksitas hidup modern, kita sering kali menghadapi masalah yang
                        memerlukan bantuan cepat dan andal. Solusi serbaguna hadir untuk menjawab berbagai tantangan
                        sehari-hari.
                    </p>
                    <p>Mulai dari kebutuhan transportasi harian, pekerjaan rumah yang menumpuk, hingga tugas-tugas
                        khusus yang tidak selalu bisa diatasi sendiri. Inilah mengapa <span
                            class="fw-semibold">ToHelpSerbaBisa</span> hadir – untuk mempermudah hidup Anda dengan
                        menyediakan berbagai layanan yang dapat disesuaikan dengan kebutuhan spesifik Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="">
        <div class="service-block position-relative bg-secondary">
            <div class="jarallax service-bg"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/ojek.png); background-size: cover; background-repeat: no-repeat; background-position: center; ">
            </div>
            <div class="container service-content position-absolute top-50 start-50 translate-middle">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <h3 class="display-6 fw-semibold text-white mb-4">Transportasi (Ngojek)</h3>
                        <p class="text-white">Layanan ojek kami siap mengantar Anda ke mana saja dengan cepat dan aman.
                        </p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <a href="{{ route('ojek') }}" class="btn btn-outline-light service-btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-block position-relative bg-secondary">
            <div class="jarallax service-bg"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/ojek.png); background-size: cover; background-repeat: no-repeat; background-position: center; ">
            </div>
            <div class="container service-content position-absolute top-50 start-50 translate-middle">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <h3 class="display-6 fw-semibold text-white mb-4">Transportasi (Mobil)</h3>
                        <p class="text-white">Layanan mobil kami siap mengantar Anda ke mana saja dengan cepat dan aman.
                        </p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <a href="{{ route('taxi') }}" class="btn btn-outline-light service-btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-block position-relative bg-secondary">
            <div class="jarallax service-bg"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/bersih-rumah.png); background-size: cover; background-repeat: no-repeat; background-position: center;">
            </div>
            <div class="container service-content position-absolute top-50 start-50 translate-middle">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <h3 class="display-6 fw-semibold text-white mb-4">Bersih-Bersih Rumah</h3>
                        <p class="text-white">Layanan pembersihan rumah profesional untuk memastikan lingkungan Anda
                            tetap bersih dan nyaman.</p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <a href="{{ route('bersih') }}" class="btn btn-outline-light service-btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-block position-relative bg-secondary">
            <div class="jarallax service-bg"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/jasa-angkut.jpg); background-size: cover; background-repeat: no-repeat; background-position: center;">
            </div>
            <div class="container service-content position-absolute top-50 start-50 translate-middle">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <h3 class="display-6 fw-semibold text-white mb-4">Jasa Pindahan/Angkut Barang</h3>
                        <p class="text-white">Layanan angkut barang kami membantu Anda memindahkan barang ke mana saja
                            dengan cepat, aman, dan efisien.</p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <a href="{{ route('pindahan') }}" class="btn btn-outline-light service-btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-block position-relative bg-secondary">
            <div class="jarallax service-bg"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(images/jasa-custom.jpg); background-size: cover; background-repeat: no-repeat; background-position: center;">
            </div>
            <div class="container service-content position-absolute top-50 start-50 translate-middle">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <h3 class="display-6 fw-semibold text-white mb-4">Jasa Sesuai Permintaan</h3>
                        <p class="text-white">Dari membantu belanja, mengurus keperluan administrasi, hingga
                            tugas-tugas khusus lainnya, kami siap melayani Anda.</p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <a href="{{ route('kustom') }}" class="btn btn-outline-light service-btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="padding-small" style="margin-top: -10px;">
        <div class="container">
            <div class="row">
                <div class="text-center col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <svg class="text-primary me-1" width="42" height="42">
                        <use xlink:href="#clock-square"></use>
                    </svg>
                    <h5 class="mt-3">Layanan Serba Ada</h5>
                    <p>ToHelp tidak terbatas pada satu jenis layanan saja. Apapun kebutuhan Anda, kami siap membantu.
                    </p>
                </div>
                <div class="text-center col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <svg class="text-primary me-1" width="42" height="42">
                        <use xlink:href="#user-speak"></use>
                    </svg>
                    <h5 class="mt-3">Fleksibilitas dan Kenyamanan</h5>
                    <p>Kami menawarkan kemudahan dalam memesan layanan sesuai kebutuhan Anda, kapan saja dan di mana
                        saja.</p>
                </div>
                <div class="text-center col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <svg class="text-primary me-1" width="42" height="42">
                        <use xlink:href="#tag-price"></use>
                    </svg>
                    <h5 class="mt-3">Tenaga Kerja Muda dan Energik</h5>
                    <p>Tim kami terdiri dari para pemuda yang berdedikasi tinggi dan energik, siap memberikan layanan
                        terbaik untuk Anda.</p>
                </div>
                <div class="text-center col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <svg class="text-primary me-1" width="42" height="42">
                        <use xlink:href="#mask-happly"></use>
                    </svg>
                    <h5 class="mt-3">Komitmen pada Kualitas Terbaik</h5>
                    <p>Kami selalu berupaya memberikan pelayanan dengan standar tinggi untuk memastikan kepuasan
                        pelanggan.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="my-5"
        style="background-image: linear-gradient(rgba(5, 33, 60, 0.5), rgba(5, 33, 60, 0.5)), url(images/hero-bg.png);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;">
        <div class="container padding-small">
            <div class="row g-lg-5 align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h6 class="text-white">Pesan Sekarang</h6>
                    <h2 class="display-4 fw-bold text-white">Pelayanan Terbaik dari Kami</h2>
                    <p class="text-white">Bergabunglah dengan komunitas ToHelp dan rasakan sendiri kemudahan dan
                        kenyamanan yang kami tawarkan. Hidup Anda menjadi lebih mudah dan teratur dengan layanan kami
                        yang dapat diandalkan. Kami hadir untuk membantu, kapan saja dan di mana saja.</p>
                    <ul class="d-flex flex-wrap align-items-center list-unstyled m-0">
                        <li class="time text-white text-capitalize d-flex align-items-center me-4">
                            <div class="text-lg-end">
                                <a href="javascript:void(0)" onclick="sendWhatsAppMessage()"
                                    class="btn btn-outline-light service-btn">
                                    Hubungi Kami
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="quote" class="padding-small">
        <div class="container text-center">
            <h3 class="display-6 fw-semibold">Testimonimu Adalah Semangat Kami</h3>
            <form name="tohelpserbabisa-testimonial-form">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                    <input type="text" name="nama" placeholder="Nama*"
                        class="form-control w-100 ps-3 py-2 rounded-0" required>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                    <!-- Star Rating -->
                    <div class="star-rating">
                        <input type="hidden" name="rating" id="rating-value" required>
                        <i class="fas fa-star" data-rating="5"></i>
                        <i class="fas fa-star" data-rating="4"></i>
                        <i class="fas fa-star" data-rating="3"></i>
                        <i class="fas fa-star" data-rating="2"></i>
                        <i class="fas fa-star" data-rating="1"></i>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-4">
                    <textarea class="form-control w-100 ps-3 py-2 rounded-0" rows="6" type="text" name="ulasan"
                        placeholder="Ulasan*" required></textarea>
                </div>
                <div class="col-12">
                    <button onclick="submitTestimoni()" class="btn btn-primary btn-slide hover-slide-right mt-4"
                        id="submit-btn">
                        <span id="button-text">Kirim</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section id="testimonial">
        <div class="container">
            <h6><span class="text-primary">|</span>Testimoni</h6>
            <h3 class="display-6 fw-semibold mb-5">Kata Mereka tentang kami</h3>
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper" id="testimonial-wrapper">
                    <!-- Testimonial items will be inserted here -->
                    @foreach ($testimonis as $testimoni)
                        <div class="swiper-slide">
                            <div class="review-item">
                                <div class="review">
                                    <blockquote class="mb-0">
                                        <p class="mb-0" style="white-space: pre-wrap;">{{ $testimoni->komentar }}</p>
                                    </blockquote>
                                </div>
                                <div class="author-detail mt-4 d-flex align-items-center">
                                    <div class="author-text">
                                        <h5 class="name mb-0">{{ $testimoni->nama }}</h5>
                                        <div class="review-star d-flex mt-2">
                                            @for ($i = 0; $i < $testimoni->rating; $i++)
                                                <svg class="star me-1 text-warning" width="16" height="16">
                                                    <use xlink:href="#star" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination position-relative pt-5"></div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><svg
                            class="bi" width="40" height="40">
                            <use xlink:href="#close-sharp"></use>
                        </svg></button>
                    <div class="ratio ratio-16x9">
                        <video controls>
                            <source src="{{ asset('images/movie.mov') }}" type="video/mp4">
                            Your browser does not support the video tag.
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk mengirim pesan WhatsApp
        function sendWhatsAppMessage() {
            const message = "Hii minhelp, saya membutuhkan bantuan To Help sekarang";
            const phoneNumber = "6285695908981";
            const whatsappUrl = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }

        function submitTestimoni() {
            const form = document.forms['tohelpserbabisa-testimonial-form'];
            const nama = form['nama'].value;
            const rating = form['rating'].value;
            const ulasan = form['ulasan'].value;

            if (!nama || !rating || !ulasan) {
                alert('Silakan lengkapi data terlebih dahulu');
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            const buttonText = document.getElementById('button-text');
            submitBtn.disabled = true;
            buttonText.innerText = 'Mengirim...';

            $.ajax({
                url: '{{ route('testimoni') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama: nama,
                    rating: rating,
                    komentar: ulasan
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        toastr.success('Testimonial Anda telah berhasil dikirim');
                        form.reset();
                        window.location.reload();
                    } else {
                        toastr.error('Maaf, terjadi kesalahan. Silakan coba lagi.');
                    }
                },
                error: function(error) {
                    console.error(error);
                    toastr.error(error.responseJSON.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.');
                },
                complete: function() {
                    submitBtn.disabled = false;
                    buttonText.innerText = 'Kirim';
                }
            });
        }

        // Inisialisasi video player
        function initVideoPlayer() {
            const modal = document.getElementById('myModal');
            if (!modal) return;

            const video = modal.querySelector('video');
            if (!video) return;

            // Pause video when modal is closed
            modal.addEventListener('hidden.bs.modal', function() {
                video.pause();
            });
        }

        // Initialize all components when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get all star elements
            const stars = document.querySelectorAll('.star-rating .fas.fa-star');
            const ratingInput = document.getElementById('rating-value');

            // Initialize stars with gray color
            stars.forEach(star => {
                star.style.color = '#ccc'; // Default gray color

                // Add click event listener to each star
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    setRating(rating);
                });

                // Add hover effects
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    highlightStars(rating);
                });
            });

            // Add mouseleave event to the star container to reset to selected rating
            document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                const currentRating = ratingInput.value || 0;
                highlightStars(currentRating);
            });

            // Function to set the rating
            function setRating(rating) {
                // Set the hidden input value
                ratingInput.value = rating;

                // Highlight the stars permanently
                highlightStars(rating);
            }

            // Function to highlight stars
            function highlightStars(rating) {
                stars.forEach(star => {
                    const starRating = parseInt(star.getAttribute('data-rating'));

                    // If this star's rating is less than or equal to the selected rating, color it yellow
                    if (starRating <= rating) {
                        star.style.color = '#ffc107'; // Bootstrap yellow/warning color
                    } else {
                        star.style.color = '#ccc'; // Gray color for unselected stars
                    }
                });
            }
            // Initialize testimonials
            // initTestimonials();

            // Initialize video player
            initVideoPlayer();

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Initialize alerts
            document.querySelectorAll('.alert .btn-close').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.alert').classList.remove('show');
                });
            });
        });
    </script>
@endpush
