@extends('layouts.app')

@section('title', 'Create Location Details Data')

@section('content')
    <div class="pagetitle">
        <h1>Create Location Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('detail-lokasi.index') }}">Location Details Data</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- ==========alert================== --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- ================================== --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Location Details </h5>
                <form method="POST" action="{{ route('detail-lokasi.store') }}">
                    @csrf
                    <div class="row g-3">
                        <!-- Nama Rak -->
                        <div class="col-md-6">
                            <label for="nama_rak" class="form-label">Nama Rak</label>
                            <input type="text" class="form-control" name="nama_rak" id="nama_rak" required>
                        </div>

                        <!-- Pilih Plan -->
                        <div class="col-md-6">
                            <label for="id_plan" class="form-label">Plan</label>
                            <select name="id_plan" id="id_plan" class="form-select" required>
                                <option value="">Pilih Plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area: Pilih atau Tambah -->
                        <div class="col-md-6">
                            <label for="id_area" class="form-label">Pilih Area (opsional)</label>
                            <select name="id_area" id="id_area" class="form-select select2" disabled>
                                <option value="">-- Pilih Plan terlebih dahulu --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nama_area_baru" class="form-label">Atau Tambah Area Baru</label>
                            <input type="text" class="form-control" name="nama_area_baru" id="nama_area_baru"
                                placeholder="Nama Area Baru (opsional)" disabled>
                        </div>


                        <!-- Submit -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('detail-lokasi.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    {{-- select 2 --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5', // pakai tema Bootstrap 5
                xallowClear: true,
                placeholder: "-- Pilih --",
                width: '100%'
            });
        });
    </script>
    {{-- ===== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectPlan = document.getElementById('id_plan');
            const selectArea = document.getElementById('id_area');
            const inputAreaBaru = document.getElementById('nama_area_baru');

            // Load area saat plan dipilih
            selectPlan.addEventListener('change', function() {
                const planId = this.value;
                if (planId) {
                    fetch(`/get-area-by-plan/${planId}`)
                        .then(response => response.json())
                        .then(data => {
                            selectArea.innerHTML = `<option value="">-- Pilih Area --</option>`;
                            data.forEach(area => {
                                const option = document.createElement('option');
                                option.value = area.id;
                                option.textContent = area.nama_area;
                                selectArea.appendChild(option);
                            });
                            selectArea.disabled = false;
                            validateAreaInputs();
                        });
                } else {
                    selectArea.innerHTML = `<option value="">-- Pilih Plan terlebih dahulu --</option>`;
                    selectArea.disabled = true;
                    inputAreaBaru.disabled = true;
                }
            });

            // Fungsi validasi input area vs area baru
            function validateAreaInputs() {
                const areaSelected = selectArea.value !== '';
                const areaBaruFilled = inputAreaBaru.value.trim() !== '';

                // Jika pilih area → area baru disable
                if (areaSelected) {
                    inputAreaBaru.disabled = true;
                } else {
                    inputAreaBaru.disabled = false;
                }

                // Jika isi area baru → select area disable
                if (areaBaruFilled) {
                    selectArea.disabled = true;
                } else if (selectPlan.value) {
                    selectArea.disabled = false;
                }
            }

            // Listener perubahan input
            selectArea.addEventListener('change', validateAreaInputs);
            inputAreaBaru.addEventListener('input', validateAreaInputs);
        });
    </script>


@endsection
