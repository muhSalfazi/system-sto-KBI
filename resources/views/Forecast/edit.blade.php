@extends('layouts.app')

@section('title', 'Edit Forecast')

@section('content')
    <div class="pagetitle">
        <h1>Edit Forecast</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('forecast.index') }}">Forecast</a></li>
                <li class="breadcrumb-item active">Edit Forecast</li>
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
                <h5 class="card-title">Update Forecast:
                    {{ $forecast->part->Inv_id ?? '-' }} | {{ $forecast->part->Part_name ?? '-' }}</h5>

                <form action="{{ route('forecast.update', $forecast->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="forecast_month" class="form-label">Forecast Bulan</label>
                        <input type="month" name="forecast_month" class="form-control"
                            value="{{ \Carbon\Carbon::parse($forecast->forecast_month)}}" required>
                    </div>

                    <div class="mb-3">
                        <label for="qty_box" class="form-label">Qty/Box</label>
                        <input type="number" name="qty_box" class="form-control" value="{{ $forecast->qty_box }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="po_pcs" class="form-label">Jumlah PO (pcs)</label>
                        <input type="number" name="po_pcs" class="form-control" value="{{ $forecast->PO_pcs }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="hari_kerja" class="form-label">Hari Kerja</label>
                        <input type="number" name="hari_kerja" class="form-control" value="{{ $forecast->hari_kerja }}"
                            required min="1" max="31">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('forecast.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
@endsection
