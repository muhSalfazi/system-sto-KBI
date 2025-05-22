@extends('layouts.app')

@section('title', 'Input Forecast')

@section('content')
    <div class="pagetitle">
        <h1>Create Forecast Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('forecast.index') }}">Forecast Data</a></li>
                <li class="breadcrumb-item active">Create Forecast Data</li>
            </ol>
        </nav>
    </div>

    <section class="section">
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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Input Forecast</h5>

                <form action="{{ route('forecast.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="id" class="form-label">Part (Inv ID)</label>
                        <select name="id" id="id" class="form-select select2" required>
                            <option value="">-- Pilih Part --</option>
                            @foreach ($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->Inv_id }} - {{ $part->customer->username }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="forecast_month" class="form-label">Forecast Bulan</label>
                        <input type="month" name="forecast_month" id="forecast_month" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="po_pcs" class="form-label">Jumlah PO (pcs)</label>
                        <input type="number" name="po_pcs" id="po_pcs" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="hari_kerja" class="form-label">Hari Kerja</label>
                        <input type="number" name="hari_kerja" id="hari_kerja" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('forecast.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
