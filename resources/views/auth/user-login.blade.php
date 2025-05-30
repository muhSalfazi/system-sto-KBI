<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Login User</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('assets/img/icon-kbi.png') }}" loading="lazy" alt="logo" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-auth.css') }}" rel="stylesheet">


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
                                <img src="{{ asset('assets/img/kyoraku-baru.png') }}" alt="Logo Kyoraku"
                                    class="logo-auth" loading="lazy">
                            </div>
                            <!-- End Logo -->

                            {{-- <div class="logo d-flex align-items-center w-auto">
                            </div> --}}
                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login To User</h5>
                                        <p class="text-center small">Enter your Id Card Number to login</p>
                                    </div>

                                    <form class="row g-3 needs-validation" novalidate
                                        action="{{ route('user.login.post') }}" method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <label for="yournik" class="form-label">Id Card Number</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-person-vcard-fill"></i>
                                                </span>
                                                <input type="text" name="nik"
                                                    class="form-control @error('nik') is-invalid @enderror"
                                                    id="yournik" value="{{ old('nik') }}" autocomplete="off">
                                                @error('nik')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- route login user --}}
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary fw-bold w-100"
                                                style="font-size: 0.875rem; padding: 4px 8px;">Login</button>
                                        </div>
                                        <div class="col-12">
                                            <a href="{{ route('admin.login') }}"
                                                class="btn btn-outline-secondary w-100"
                                                style="font-size: 0.875rem; padding: 4px 8px;">
                                                <i class="bi bi-box-arrow-in-right"></i> Login Admin
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="last-update-text">
                                Last Update: 29 Mei 2025
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
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown' // Menambahkan animasi muncul
                },
                title: 'Gagal Login',
                text: '{{ session('warning') }}',
            });
        </script>
    @endif
    {{-- <script>
        // SweetAlert for validation errors
        const validationErrors = @json($errors->all());
        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: validationErrors.join('<br>'),
            });
        }
    </script> --}}
</body>

</html>
