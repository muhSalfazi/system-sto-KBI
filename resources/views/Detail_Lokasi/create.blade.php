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
                            <select name="id_area" id="id_area" class="form-select">
                                <option value="">-- Pilih Area --</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nama_area_baru" class="form-label">Atau Tambah Area Baru</label>
                            <input type="text" class="form-control" name="nama_area_baru" id="nama_area_baru"
                                placeholder="Nama Area Baru (opsional)">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectArea = document.getElementById('id_area');
            const inputAreaBaru = document.getElementById('nama_area_baru');

            // Disable inputAreaBaru saat selectArea punya value
            selectArea.addEventListener('change', function() {
                if (this.value) {
                    inputAreaBaru.disabled = true;
                    inputAreaBaru.value = '';
                } else {
                    inputAreaBaru.disabled = false;
                }
            });

            // Disable selectArea saat user isi inputAreaBaru
            inputAreaBaru.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    selectArea.disabled = true;
                    selectArea.value = '';
                } else {
                    selectArea.disabled = false;
                }
            });
        });
    </script>
@endsection
