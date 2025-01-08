<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #05213C;">
        <div class="container">
            <a class="navbar-brand py-2" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link px-3 text-white" href="{{ route('index') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-white" href="#about-us">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-white" href="#services">Pelayanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-white" href="#cta">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<style>
    .navbar .nav-link {
        font-weight: 500;
        position: relative;
    }

    .navbar .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        background: white;
        left: 0;
        bottom: 0;
        transition: width 0.3s;
    }

    .navbar .nav-link:hover::after,
    .navbar .nav-link.active::after {
        width: 100%;
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: #05213C;
            padding: 1rem;
            margin-top: 0.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        // Handle click events
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Handle scroll events
        window.addEventListener('scroll', () => {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;

                if (window.scrollY >= sectionTop && window.scrollY < sectionTop +
                    sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
                // Special case for home/beranda
                if (current === '' && link.getAttribute('href') === '#') {
                    link.classList.add('active');
                }
            });
        });
    });
</script>
