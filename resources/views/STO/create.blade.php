@extends('layouts.app')

@section('title', 'Input LIST STO')

@section('content')
    <div class="pagetitle">
        <h1>Create STO List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sto.index') }}">List Data Sto</a></li>
                <li class="breadcrumb-item active">Create STO List</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- ==========alert ============ --}}
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
        {{-- ============================ --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Input STO</h5>
                <form action="{{ route('sto.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="id_part" class="form-label">Part</label>
                        <select name="id_part" id="id_part" class="form-select select2" required>
                            <option value="">-- Pilih Part --</option>
                            @foreach ($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->Part_name }} - {{ $part->Part_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="plan_stock" class="form-label">Plan Stok</label>
                        <input type="text" name="plan_stock" id="plan_stock" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('sto.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "-- Pilih --",
                width: '100%'
            });
        });
    </script>

@endsection
