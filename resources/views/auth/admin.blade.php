<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('assets/img/icon-kbi.png') }}" loading="lazy" alt="logo" type="image/png">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-auth.css') }}" rel="stylesheet">

    <!-- SweetAlert CSS -->
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>

</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                           <div class="logo-wrapper text-center py-3">
                            <img src="{{ asset('assets/img/kyoraku-baru.png') }}" alt="Logo Kyoraku" class="logo-auth">
                        </div>
                        <!-- End Logo -->

                            {{-- <div class="logo d-flex align-items-center w-auto">
                            </div> --}}
                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Admin</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <form class="row g-3 needs-validation" novalidate action="{{ route('admin.login.post') }}"
                                        method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <input type="text" name="username" class="form-control"
                                                    id="yourUsername" value="{{ old('username') }}" required>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-key"></i>
                                                </span>
                                                <input type="password" name="password" class="form-control"
                                                    id="yourPassword" required>
                                                <span class="input-group-text" id="togglePassword">
                                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                                </span>
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            </div>
                                        </div>
                                        {{-- route login user --}}
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary fw-bold w-100"
                                            style="font-size: 0.875rem; padding: 4px 8px;">Login</button>
                                        </div>
                                        <div class="col-12">
                                            <a href="#" class="btn btn-outline-secondary w-100" style="font-size: 0.875rem; padding: 4px 8px;">
                                                <i class="bi bi-box-arrow-in-right"></i> Login User
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="credits">
                                &copy; Sto Management System 2025
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#yourPassword');
        const togglePasswordIcon = document.querySelector('#togglePasswordIcon');

        togglePassword.addEventListener('click', function(e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // toggle the eye / eye-slash icon
            togglePasswordIcon.classList.toggle('bi-eye');
            togglePasswordIcon.classList.toggle('bi-eye-slash');
        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login',
                text: '{{ session('error') }}',
            });
        </script>
    @endif
    <script>
        // SweetAlert for validation errors
        const validationErrors = @json($errors->all());
        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: validationErrors.join('<br>'),
            });
        }
    </script>
</body>

</html>
