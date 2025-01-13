@extends('layouts.app')
@push('styles')
    <style>
        .star-rating {
            font-size: 24px;
            cursor: pointer;
        }

        .star-rating i {
            color: #ccc;
            transition: color 0.2s;
        }

        .star-rating i.active {
            color: #ffd700;
        }

        .star-rating i:hover,
        .star-rating i:hover~i {
            color: #ffd700;
        }

        .star-rating {
            text-align: center;
        }

        .star-rating i {
            display: inline-block;
            padding: 0 5px;
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
                        <img class="img-fluid" src="{{ asset('images/about.png') }}" alt="img">
                        <div class="video-player position-absolute top-50 start-50 translate-middle">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#myModal"
                                class="play-btn position-relative">
                                <svg class="play-icon text-primary my-3" width="80" height="80">
                                    <use xlink:href="#play-button"></use>
                                </svg>
                        </div>
                        </a>
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
                        <a href="https://api.whatsapp.com/send?phone=6285695908981&text=Hii minhelp, saya membutuhkan bantuan To Help sekarang"
                            class="btn btn-outline-light service-btn">Pesan Sekarang</a>
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
                <!-- <div class="col-lg-6 mb-5 mb-lg-0">
                                                                                                                      <div class="border-dotted rounded-4 p-5">
                                                                                                                        <h2 class="display-4 fw-bold text-white">$30 Off</h2>
                                                                                                                        <p class="fs-3 fw-semibold text-white text-capitalize">if you Mention our Website</p>
                                                                                                                        <p class="text-white">RPurus pulvinar feugiat orci dictumst ellentesque ut sem partu rient. Purus pulvinar
                                                                                                                          feugiat orci dictumst ellentesque ut sem partu rien.</p>
                                                                                                                      </div>
                                                                                                                    </div> -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h6 class="text-white">Pesan Sekarang</h6>
                    <h2 class="display-4 fw-bold text-white">Pelayanan Terbaik dari Kami</h2>
                    <p class="text-white">Bergabunglah dengan komunitas ToHelp dan rasakan sendiri kemudahan dan
                        kenyamanan yang kami tawarkan. Hidup Anda menjadi lebih mudah dan teratur dengan layanan kami
                        yang dapat diandalkan. Kami hadir untuk membantu, kapan saja dan di mana saja.</p>
                    <ul class="d-flex flex-wrap align-items-center list-unstyled m-0">
                        <li class="time text-white text-capitalize d-flex align-items-center me-4">
                            <div class="text-lg-end">
                                <a href="index.html" class="btn btn-outline-light service-btn">Hubungi Kami</a>
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
            <!-- Add alert containers -->
            <div class="alert alert-success alert-dismissible fade" id="success-alert" role="alert">
                Terima kasih! Testimonial Anda telah berhasil dikirim.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="alert alert-danger alert-dismissible fade" id="error-alert" role="alert">
                Maaf, terjadi kesalahan. Silakan coba lagi.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <form name="tohelpserbabisa-testimonial-form">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                    <input type="text" name="nama" placeholder="Nama*"
                        class="form-control w-100 ps-3 py-2 rounded-0" required>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                    <!-- Star Rating -->
                    <div class="star-rating">
                        <input type="hidden" name="rating" id="rating-value" required>
                        <i class="fas fa-star" data-rating="1"></i>
                        <i class="fas fa-star" data-rating="2"></i>
                        <i class="fas fa-star" data-rating="3"></i>
                        <i class="fas fa-star" data-rating="4"></i>
                        <i class="fas fa-star" data-rating="5"></i>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-4">
                    <textarea class="form-control w-100 ps-3 py-2 rounded-0" rows="6" type="text" name="ulasan"
                        placeholder="Ulasan*" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-slide hover-slide-right mt-4" id="submit-btn">
                        <span id="button-text">Kirim</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section id="testimonial">
        <div class="container">
            <h6><span class="text-primary">|</span>Testimoni</h6>
            <h3 class="display-6 fw-semibold mb-5">Kustomer kami</h3>
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper" id="testimonial-wrapper">
                    <!-- Testimonial items will be inserted here -->
                </div>
                <div class="swiper-pagination position-relative pt-5"></div>
            </div>
        </div>
    </section>

    <!-- Video Popup -->
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
        // Config
        const config = {
            spreadsheetId: '{{ env('SPREADSHEET_ID') }}',
            scriptUrl: '{{ env('SPREADSHEET_SCRIPT_URL') }}'
        };

        function initTestimonials() {
            const form = document.forms['tohelpserbabisa-testimonial-form'];
            const wrapper = document.getElementById('testimonial-wrapper');
            let testimonials = [];
            let swiper = null;
            let isLoading = false;

            async function loadTestimonials() {
                try {
                    const url = `https://docs.google.com/spreadsheets/d/${config.spreadsheetId}/export?format=csv`;
                    const response = await fetch(url);
                    const data = await response.text();

                    if (!data || data.trim() === '') {
                        testimonials = [];
                        wrapper.innerHTML = '';
                        return;
                    }

                    const rows = data.split('\n');
                    if (rows.length <= 1) {
                        testimonials = [];
                        wrapper.innerHTML = '';
                        return;
                    }

                    testimonials = parseCSV(data);
                    renderTestimonials();
                } catch (error) {
                    console.error('Load error:', error);
                    testimonials = [];
                    wrapper.innerHTML = `<p class="text-danger">Error loading testimonials</p>`;
                }
            }

            function parseCSV(csv) {
                return csv.split('\n')
                    .slice(1)
                    .map(line => {
                        if (!line.trim()) return null;
                        const [timestamp, name, review, rating] = line.split(',');
                        return {
                            timestamp,
                            name: name?.trim() || '',
                            review: review?.trim() || '',
                            rating: parseInt(rating) || 0
                        };
                    })
                    .filter(row => row && row.name && row.review);
            }

            function renderTestimonials() {
                if (!testimonials.length) {
                    wrapper.innerHTML = '';
                    return;
                }

                const html = testimonials.map(t => `
                    <div class="swiper-slide">
                        <div class="review-item">
                        <div class="review">
                            <blockquote class="mb-0">
                            <p class="mb-0">"${t.review}"</p>
                            </blockquote>
                        </div>
                        <div class="author-detail mt-4 d-flex align-items-center">
                            <div class="author-text">
                            <h5 class="name mb-0">${t.name}</h5>
                            <div class="review-star d-flex mt-2">
                                ${generateStars(t.rating)}
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    `).join('');

                wrapper.innerHTML = html;
                initSwiper();
            }

            function generateStars(rating) {
                return Array(5).fill(0)
                    .map((_, i) => `<svg class="star me-1 ${i < rating ? 'text-warning' : 'text-muted'}" width="16" height="16">
        <use xlink:href="#star" />
      </svg>`)
                    .join('');
            }

            function initSwiper() {
                if (swiper) swiper.destroy();
                if (!document.querySelector('.testimonial-swiper .swiper-slide')) return;

                swiper = new Swiper('.testimonial-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2
                        },
                        1024: {
                            slidesPerView: 3
                        }
                    }
                });
            }

            async function handleSubmit(e) {
                e.preventDefault();
                if (isLoading) return;

                const formData = new FormData(form);
                const name = formData.get('nama')?.trim();
                const review = formData.get('ulasan')?.trim();
                const rating = document.getElementById('rating-value')?.value;

                if (!name) {
                    alert('Mohon masukkan nama Anda');
                    return;
                }

                if (!rating) {
                    alert('Mohon berikan rating');
                    return;
                }

                if (!review) {
                    alert('Mohon berikan ulasan');
                    return;
                }

                // Check for duplicates if testimonials are loaded
                if (testimonials.length > 0 && testimonials.some(t => t.name.toLowerCase() === name.toLowerCase())) {
                    alert('Anda sudah memberikan testimoni sebelumnya');
                    return;
                }

                isLoading = true;
                const submitBtn = document.getElementById('submit-btn');
                const buttonText = document.getElementById('button-text');
                submitBtn.disabled = true;
                buttonText.textContent = 'Mengirim...';

                try {
                    const response = await fetch(config.scriptUrl, {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        const successAlert = document.getElementById('success-alert');
                        successAlert.classList.add('show');
                        form.reset();
                        document.querySelectorAll('.star-rating i').forEach(star => star.classList.remove('active'));
                        document.getElementById('rating-value').value = '';
                        await loadTestimonials();
                    } else {
                        throw new Error('Gagal mengirim');
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    const errorAlert = document.getElementById('error-alert');
                    errorAlert.classList.add('show');
                } finally {
                    isLoading = false;
                    submitBtn.disabled = false;
                    buttonText.textContent = 'Kirim';
                }
            }

            const stars = document.querySelectorAll('.star-rating i');
            const ratingInput = document.getElementById('rating-value');

            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    const rating = star.getAttribute('data-rating');
                    stars.forEach(s => {
                        s.classList.toggle('active', s.getAttribute('data-rating') <= rating);
                    });
                });

                star.addEventListener('mouseout', () => {
                    const rating = ratingInput.value;
                    stars.forEach(s => {
                        s.classList.toggle('active', rating && s.getAttribute('data-rating') <=
                            rating);
                    });
                });

                star.addEventListener('click', () => {
                    ratingInput.value = star.getAttribute('data-rating');
                });
            });

            form.addEventListener('submit', handleSubmit);
            loadTestimonials();
        }

        document.addEventListener('DOMContentLoaded', initTestimonials);
    </script>
@endpush
