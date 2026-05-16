<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STBM PUSKESMAS TIRON</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

    <!-- data aos (efek mucnul satu satu) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        body {
            background-color: #F8F9FA;
        }

        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: #198754;
        }

        .hero-text {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .section-title {
            font-weight: 700;
            color: #198754;
        }

        #map {
            height: 450px;
            border-radius: 20px;
        }

        .about-carousel {
            max-width: 450px;
            margin: auto;
            border-radius: 20px;
            overflow: hidden;
        }

        .about-carousel .carousel-item {
            position: relative;
        }

        .about-carousel img {
            height: 350px;
            object-fit: cover;
            border-radius: 20px;
        }

        .about-carousel .carousel-item::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.2),
                    rgba(0, 0, 0, 0.1));
            border-radius: 20px;
        }

        .about-carousel .carousel-caption {
            z-index: 2;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">

            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ asset('storage/images/logo.png') }}"
                    alt=""
                    width="35"
                    height="35"
                    class="me-2">
                STBM PUSKESMAS TIRON
            </a>

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="#statistik">Statistik</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#peta">Peta</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>

                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ route('login') }}"
                            class="btn btn-success rounded-pill px-4">
                            Login Admin
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <section class="hero" data-aos="fade-up">
        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <h1 class="hero-title mb-4">
                        Sistem Monitoring STBM
                    </h1>

                    <p class="hero-text mb-4">
                        Monitoring Sanitasi Total Berbasis Masyarakat
                        secara digital untuk mendukung lingkungan
                        yang sehat di Kecamatan Banyakan.
                    </p>

                    <a href="#statistik" class="btn btn-success btn-lg rounded-pill px-4">
                        Lihat Statistik
                    </a>

                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/landing_page/logo_stbm.png') }}" alt="logo stbm">
                </div>

            </div>

        </div>
    </section>

    <section id="statistik" class="py-5" data-aos="fade-up">
        <div class="container">

            <h2 class="section-title text-center mb-5">
                Statistik STBM
            </h2>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm p-4 text-center">
                        <i class="bi bi-house-fill text-success fs-1 mb-3"></i>
                        <h3>{{$totalkk}}</h3>
                        <p class="text-muted mb-0">Total KK</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm p-4 text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                        <h3>{{$selesai}}</h3>
                        <p class="text-muted mb-0">STBM Selesai</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm p-4 text-center">
                        <i class="bi bi-emoji-smile-fill text-success fs-1 mb-3"></i>
                        <h3>{{$layak}}</h3>
                        <p class="text-muted mb-0">Rumah Layak</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm p-4 text-center">
                        <i class="bi bi-geo-alt-fill text-success fs-1 mb-3"></i>
                        <h3>{{$desa}}</h3>
                        <p class="text-muted mb-0">Total Desa</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="py-5" data-aos="fade-up">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Kondisi STBM per Desa
                    </h5>

                    <div style="height: 400px;">
                        <canvas id="desaChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="peta" class="py-5" data-aos="fade-up">
        <div class="container">

            <h2 class="section-title text-center mb-5">
                Peta Sebaran STBM
            </h2>

            <div id="map" class="shadow-sm"></div>

        </div>
    </section>

    <section id="tentang" class="py-5 bg-white" data-aos="fade-up">
        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div id="carouselExampleCaptions" class="carousel slide about-carousel" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{asset('storage/landing_page/lp1.jpeg')}}" class="d-block w-100" alt="lp1">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Pemicuan STMB pilar 1 - 5 di desa Tron kecanatan banyakan kab kediri</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{asset('storage/landing_page/lp2.jpeg')}}" class="d-block w-100" alt="lp2">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Inspeksi kualitas air minum</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{asset('storage/landing_page/lp3.jpeg')}}" class="d-block w-100" alt="lp3">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Implementasi Cuci tangan pakai sabun</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{asset('storage/landing_page/lp4.jpeg')}}" class="d-block w-100" alt="lp4">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Deklarasi ODF</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{asset('storage/landing_page/lp5.jpeg')}}" class="d-block w-100" alt="lp5">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Surveilans kualitas air minum rumah tangga</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                </div>

                <div class="col-lg-6">

                    <h2 class="section-title mb-4">
                        Tentang STBM
                    </h2>

                    <p class="text-muted">
                        Sanitasi Total Berbasis Masyarakat (STBM)
                        merupakan pendekatan untuk mengubah perilaku
                        higienis dan sanitasi melalui pemberdayaan masyarakat.
                    </p>

                    <div class="mt-4">

                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Stop Buang Air Besar Sembarangan
                        </div>

                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Cuci Tangan Pakai Sabun
                        </div>

                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Pengelolaan Air Minum
                        </div>

                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Pengelolaan Sampah Rumah Tangga
                        </div>

                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Pengelolaan Limbah Cair Rumah Tangga
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    <footer class="bg-success text-white py-4">
        <div class="container text-center">

            <p class="mb-1 fw-bold">
                STBM PUSKESMAS TIRON
            </p>

        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>

    <!-- CHART -->
    <script>
        const rekomendasi = @json($rekomendasi);

        // ambil key desa
        const desaLabels = Object.keys(rekomendasi);

        // ubah object jadi array values
        const rekomendasiValues = Object.values(rekomendasi);

        // rasio layak
        const rasioLayak = rekomendasiValues.map(item => {

            if (item.total_kk == 0) {
                return 0;
            }

            return Number(
                ((item.kk_layak / item.total_kk) * 100).toFixed(2)
            );
        });

        // status desa
        const statusDesaChart = rekomendasiValues.map(item => item.status);

        // warna
        const barColors = statusDesaChart.map(status => {

            if (status === 'Layak') {
                return 'rgba(25, 135, 84, 0.8)';
            }

            if (status === 'Cukup') {
                return 'rgba(255, 193, 7, 0.8)';
            }

            if (status === 'Tidak Layak') {
                return 'rgba(220, 53, 69, 0.8)';
            }

            return 'rgba(108, 117, 125, 0.8)';
        });

        const ctx = document
            .getElementById('desaChart')
            .getContext('2d');

        new Chart(ctx, {

            type: 'bar',

            data: {
                labels: desaLabels,

                datasets: [{
                    label: 'Rasio Rumah Layak (%)',
                    data: rasioLayak,
                    backgroundColor: barColors,
                    borderRadius: 10
                }]
            },

            options: {

                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {

                    y: {
                        beginAtZero: true,
                        max: 100,

                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const statusDesa = @json($statusDesa);
    </script>
    <script src="{{ asset('js/peta.js') }}"></script>

</body>

</html>