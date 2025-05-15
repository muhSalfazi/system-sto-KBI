@extends('layouts.user')

@section('contents')
 @if (session('report'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <a href="{{ route('reports.print', session('report')) }}" class="text-black text-decoration-underline">
            Print PDF
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif



    {{-- Main Content --}}

    <div class="container">
        <div class="card p-2 p-md-4 mt-4 shadow-lg">
            <!-- Form Packing -->
            <form action="{{ route('sto.scan') }}" method="POST" id="stoForm">
                @csrf
                <div class="mb-2">
                    <label for="inventory_id" class="form-label" style="font-size: 1.1rem;">Inventory ID (Scan QR)</label>
                    <div class="input-group my-2 my-md-3">
                        <input type="text" name="inventory_id" class="form-control" id="inventory_id"
                            placeholder="Masukkan ID Inventory" required autofocus>
                        <button class="btn btn-secondary" type="button" id="scanPart" onclick="toggleScanner()">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>
                    <button class="btn btn-primary btn-lg w-100 mt-2" type="submit" id="btnSubmit">Show</button>
                </div>
                <input type="hidden" name="action" value="show" id="actionField">
                <div class="camera-wrapper mt-3 d-flex flex-col justify-content-center">
                    <div id="reader" style="display: none; max-width: 600px;"></div>
                </div>
            </form>
            <div class="text-center">
                <a href="{{ route('sto.create') }}" class="btn btn-link mt-2 text-white" type="button" id="showFormBtn">
                    ID Inventory Kosong? Klik disini
                </a>
            </div>
        </div>

        <div class="card p-2 p-md-4 mt-4 shadow-lg">
            <!-- Form Search -->
            <form action="{{ route('sto.search') }}" method="GET" id="searchForm">
                <div class="mb-2">
                    <label for="search_query" class="form-label" style="font-size: 1.1rem;">Cari Part Name Atau
                        Number</label>
                    <div class="input-group my-2 my-md-3">
                        <input type="text" name="query" class="form-control" id="search_query"
                            placeholder="Masukkan Part Name atau Part Number" required>
                    </div>
                    <button class="btn btn-primary btn-lg w-100 mt-2" type="submit" id="btnSubmit">Search</Search></button>
                </div>
            </form>
        </div>

        {{-- Edit STO --}}
        <div class="card p-2 p-md-4 mt-4 shadow-lg">
            <div class="mb-2">
                <label for="id_report" class="form-label" style="font-size: 1.1rem;">Edit Report STO (Berdasarkan
                    Number)</label>
                <div class="input-group my-2 my-md-3">
                    <input type="text" placeholder="Enter Report Number" id="id_report" class="form-control" required>
                </div>
                <button class="btn btn-primary btn-lg w-100 mt-2" type="button" onclick="redirectToEdit()">Edit</button>
            </div>
        </div>

        <script>
            function redirectToEdit() {
                const id = document.getElementById('id_report').value;
                if (id) {
                    const url = `{{ url('/sto/form') }}/${id}`;
                    window.location.href = url;
                } else {
                    alert('Silakan masukkan ID terlebih dahulu.');
                }
            }
        </script>

    </div>
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    <!-- Tambahkan ini -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 24,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            false
        );

        function toggleScanner() {
            const reader = document.getElementById('reader');
            if (reader.style.display === 'none') {
                reader.style.display = 'block';
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            } else {
                reader.style.display = 'none';
                html5QrcodeScanner.clear();
            }
        }

        function showLoading() {
            let submitButton = document.querySelector('#btnSubmit');
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Checking Inventory...`;
            submitButton.disabled = true;
        }

        function onScanSuccess(decodedText) {
            document.getElementById('inventory_id').value = decodedText;
            document.getElementById('stoForm').submit();
            showLoading();
        }

        function onScanFailure(error) {
            console.warn(`Scan error: ${error}`);
        }
    </script>
@endsection
