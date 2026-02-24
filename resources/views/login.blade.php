<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Puskesmas Tiron</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
        }

        .login-wrapper {
            height: 100vh;
        }

        /* FORM */
        .login-form {
            background: #ffffff;
        }

        /* CAROUSEL */
        .carousel,
        .carousel-inner,
        .carousel-item {
            height: 100%;
        }

        .carousel-item > div {
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>

    <div class="container-fluid login-wrapper">
        <div class="row h-100">

            <!-- KIRI: FORM LOGIN -->
            <div class="col-12 col-md-4 d-flex align-items-center justify-content-center login-form">
                <div style="width: 100%; max-width: 500px;">

                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/images/logo.png') }}" width="80" class="mb-3">
                        <h4 class="fw-bold">UPTD PUSKESMAS TIRON</h4>
                        <small class="text-muted">Silakan login untuk melanjutkan</small>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('auth.login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                placeholder="Masukkan email" value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control" placeholder="Masukkan password">

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox"
                                    onclick="togglePasswordCheckbox(this)">
                                <label class="form-check-label">
                                    Tampilkan password
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-success w-100">
                            Login
                        </button>
                    </form>

                </div>
            </div>

            <!-- KANAN: SLIDESHOW -->
            <div class="col-md-8 d-none d-md-block p-0">
                <div id="loginCarousel"
                     class="carousel slide h-100"
                     data-bs-ride="carousel"
                     data-bs-interval="4000">

                    <div class="carousel-inner h-100">

                        <div class="carousel-item active h-100">
                            <div class="h-100 w-100"
                                style="background-image: url('{{ asset('storage/images/1.jpg') }}');">
                            </div>
                        </div>

                        <div class="carousel-item h-100">
                            <div class="h-100 w-100"
                                style="background-image: url('{{ asset('storage/images/2.jpg') }}');">
                            </div>
                        </div>

                        <div class="carousel-item h-100">
                            <div class="h-100 w-100"
                                style="background-image: url('{{ asset('storage/images/3.jpg') }}');">
                            </div>
                        </div>

                        <div class="carousel-item h-100">
                            <div class="h-100 w-100"
                                style="background-image: url('{{ asset('storage/images/4.jpg') }}');">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePasswordCheckbox(el) {
            document.getElementById('password').type = el.checked ? 'text' : 'password';
        }
    </script>

</body>
</html>
