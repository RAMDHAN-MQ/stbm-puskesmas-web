<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #198754;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar .nav-link {
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            color: #fff !important;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            transform: scale(1.05);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .profile-wrapper {
            width: 90px;
            height: 90px;
            background-color: #ffffff;
            border: 2px solid #000;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
        }

        .profile-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .page-item.active .page-link {
            background-color: #198754;
            border-color: #198754;
        }

        .page-link {
            color: #198754;
        }

        .dataTables_filter input {
            min-width: 300px;
        }

        .sidebar-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.7);
            margin: 15px 20px 5px;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <aside class="sidebar position-fixed">
            <div class="text-center py-4 border-bottom border-light">
                <div class="profile-wrapper">
                    <img src="{{ Auth::user()->foto 
                    ? asset('storage/profile/' . Auth::user()->foto) 
                    : asset('storage/images/default.png') }}"
                        alt="Foto Profil">
                </div>

                <h6 class="fw-bold mb-0 text-white">
                    {{ Auth::user()->nama ?? 'Nama Petugas' }}
                </h6>

                <small class="text-light">
                    NIP: {{ Auth::user()->nip ?? '-' }}
                </small>
            </div>


            <ul class="nav flex-column mt-3">

                <!-- LABEL: MAIN MENU -->
                <li class="sidebar-label">Main Menu</li>

                <li class="nav-item">
                    <a href="{{ route('admin.beranda.index') }}"
                        class="nav-link {{ request()->is('beranda') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.stbm.index') }}" class="nav-link">
                        <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> STBM
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.peta.index') }}" class="nav-link">
                        <i class="bi bi-pin-map me-2"></i> Peta Sebaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.rekomendasi.index') }}" class="nav-link">
                        <i class="bi bi-lightbulb me-2"></i> Rekomendasi
                    </a>
                </li>

                <!-- LABEL: MASTER DATA -->
                <li class="sidebar-label">Master Data</li>

                <li class="nav-item">
                    <a href="{{ route('admin.wilayah.index') }}" class="nav-link">
                        <i class="bi bi-globe-asia-australia me-2"></i> Kelola Wilayah
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.pegawai.index') }}"
                        class="nav-link {{ request()->is('pegawai') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Kelola Pegawai
                    </a>
                </li>

                <!-- LABEL: AKUN -->
                <li class="sidebar-label">Akun</li>

                <li class="nav-item">
                    <a href="{{ route('auth.logout') }}"
                        class="nav-link text-warning"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>

            </ul>
            <form id="logout-form"
                action="{{ route('auth.logout') }}"
                method="POST"
                class="d-none">
                @csrf
            </form>
        </aside>

        <main class="content w-100">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables js -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- cdn js chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- cdn js chart.js plugin persenan -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @stack('scripts')
</body>

</html>