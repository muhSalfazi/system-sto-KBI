<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan STO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="{{ asset('assets/img/icon-kbi.png') }}" rel="icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/form.css') }}" rel="stylesheet">
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">

    <!-- Tambahkan di <head> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <section class="section">
        <nav class="navbar navbar-form shadow-sm">
            <div class="container container-fluid">
                <div class="d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('sto.index') }}" class="text-white">
                            <i class="fas fa-warehouse me-2"></i> Scan STO
                        </a>
                    </div>
                    @if (isset($inventory))
                        <p class="colom mt-1" style="font-size: 17px; margin-bottom: -1px; color:rgb(255, 255, 255);">
                            <i class="fas fa-file-invoice"></i>&nbsp;&nbsp;Inventory ID&nbsp;:&nbsp;
                            <strong
                                style="width: 5px; font-size: 20px; color:rgb(255, 225, 0); padding: 1px; text-transform: uppercase;">
                                {{ $inventory->part->Inv_id ?? 'Not Available' }}
                            </strong>
                        </p>
                        <p class="colom mt-1" style="font-size: 17px; margin-bottom: -1px; color:rgb(255, 255, 255);">
                            <i class="fas  fa-user-tie"></i>&nbsp;&nbsp;Customer&nbsp;:&nbsp;
                            <strong
                                style="width: 5px; font-size: 20px; color:rgb(255, 213, 0); padding: 1px; text-transform: uppercase;">
                                {{ $inventory->part->customer->username ?? 'Not Available' }}
                            </strong>
                        </p>
                    @endif
                </div>
                <div class="d-flex flex-column align-items-end">
                    <div class="dropdown">
                        <div class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            <small class="d-block">
                                <i class="fas fa-user me-1" style="color:#1abc9c;"></i>
                                {{ Auth::user()->username ?? 'Guest' }}
                            </small>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-white" aria-labelledby="userDropdown">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <button type="button" class="dropdown-item text-white bg-dark"
                                        onclick="confirmLogout()">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </nav>
        @if (session('success'))
            <div class="container mt-3">
                <div id="alertSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if (session('notfound'))
            <div class="container mt-3">
                <div id="alertWarning" class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('notfound') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        @yield('contents')

    </section>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    {{-- jsload --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        window.addEventListener('load', function() {
            // Sembunyikan loader setelah halaman selesai dimuat
            document.getElementById('loader').style.display = 'none';
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
