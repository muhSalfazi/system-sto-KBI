<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Default Title')</title>
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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    /* Header tetap di atas */

    /* Custom untuk layar besar */
    @media (min-width: 1920px) {
        .swal2-popup {
            font-size: 1.5rem !important;
            /* Perbesar font untuk layar besar */
        }

        .swal2-title {
            font-size: 2rem !important;
            /* Perbesar judul */
        }

        .swal2-content {
            font-size: 1.5rem !important;
            /* Perbesar konten */
        }

        .swal2-confirm {
            font-size: 1.25rem !important;
            /* Perbesar tombol */
        }
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .swal2-popup {
            font-size: 0.875rem !important;
        }
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 15px;
        border-radius: 4px;
    }

    /* Tabel styling untuk tampilan yang lebih profesional */
    .table {
        border-collapse: collapse;
        width: 100%;
        font-size: 0.775rem;
        /* Ukuran font lebih kecil */
        border: 1px solid #dee2e6;
        /* Border tabel lebih halus */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Bayangan tabel */
    }

    .table th,
    .table td {
        vertical-align: middle;
        padding: 7px;
        /* Padding dikurangi untuk tampilan lebih ringkas */
        text-align: center;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .table th {
        background-color: #343a40;
        /* Warna latar belakang header yang lebih profesional */
        color: #ffffff;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        font-size: 0.7rem;
    }

    .table td {
        background-color: #f8f9fa;
        /* Latar belakang sel yang lebih lembut */
        color: #212529;
    }

    /* Efek hover pada baris tabel */
    .table tbody tr:hover {
        background-color: #007bff;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Tabel bergaris (striped) untuk memudahkan pembacaan */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #e9ecef;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }


    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .table td {
            font-size: 0.75rem;
            /* Ukuran font lebih kecil untuk layar kecil */
        }

        .table th {
            font-size: 0.65rem;
        }

        .table th,
        .table td {
            padding: 8px;
        }
    }

    /* Responsif untuk layar besar */
    @media (min-width: 1920px) {
        .table {
            font-size: 1rem;
        }

        .table th,
        .table td {
            padding: 9px;
        }
    }

    /* Efek animasi hover */
    .table-hover tbody tr:hover {
        background-color: #17a2b8;
        color: white;
    }

</style>
<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet">

<body>
    @include('layouts.header')

    @include('layouts.sidebar')
    <main id="main" class="main">
        @yield('content')
    </main><!-- End #main -->

    @include('layouts.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- datatable --}}
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                width: '50%', // Atur lebar
                showClass: {
                    popup: 'animate__animated animate__jackInTheBox', // Animasi saat popup muncul
                    icon: 'animate__animated animate__shakeY' // Animasi pada ikon peringatan
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp' // Animasi saat popup menghilang
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableElement = document.querySelector('.datatable');
            if (tableElement) {
                new simpleDatatables.DataTable(tableElement);
            }
        });

        @if (session('login-sukses'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{!! session('login-sukses') !!}',
                // timer: 1500,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__bounceInDown' // Menambahkan animasi muncul
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp' // Menambahkan animasi saat ditutup
                },
            });
        @endif
    </script>

</body>

</html>
