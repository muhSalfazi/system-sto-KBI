@extends('layouts.app')

@section('title', 'Edit Detail Lokasi')

@section('content')
    <div class="pagetitle">
        <h1>Edit Detail Lokasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('detail-lokasi.index') }}">Detail Lokasi</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                <h5 class="card-title">Form Edit Detail Lokasi</h5>
                <form method="POST" action="{{ route('update.detail-lokasi', $rak->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <!-- Nama Rak -->
                        <div class="col-md-6">
                            <label for="nama_rak" class="form-label">Nama Rak</label>
                            <input type="text" name="nama_rak" id="nama_rak" class="form-control"
                                value="{{ old('nama_rak', $rak->nama_rak) }}">
                        </div>

                        <!-- Plan -->
                        <div class="col-md-6">
                            <label for="plan_name" class="form-label">Plan</label>
                            <input type="text" id="plan_name" class="form-control"
                                value="{{ $rak->area->plant->name ?? '-' }}" disabled>
                        </div>

                        <!-- Area -->
                        <div class="col-md-12">
                            <label for="id_area" class="form-label">Pilih Area</label>
                            <select name="id_area" id="id_area" class="form-select">
                                <option value="">-- Pilih Area --</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" data-plant="{{ $area->id_plant }}"
                                        {{ old('id_area', $rak->id_area) == $area->id ? 'selected' : '' }}>
                                        {{ $area->nama_area }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('detail-lokasi.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idPlan = document.getElementById('id_plan');
            const idArea = document.getElementById('id_area');

            function filterAreaByPlan() {
                const selectedPlan = idPlan.value;
                Array.from(idArea.options).forEach(option => {
                    const areaPlanId = option.getAttribute('data-plant');
                    if (!selectedPlan || areaPlanId === selectedPlan) {
                        option.hidden = false;
                    } else {
                        option.hidden = true;
                    }
                });
            }

            idPlan.addEventListener('change', filterAreaByPlan);
            filterAreaByPlan(); // apply on page load
        });
    </script>
@endsection
