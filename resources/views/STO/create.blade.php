@extends('layouts.app')

@section('title', 'Input LIST STO')

@section('content')
<div class="pagetitle">
    <h1>Create Part</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parts.index') }}">Data Part</a></li>
            <li class="breadcrumb-item active">Create STO LIST</li>
        </ol>
    </nav>
</div>

    <section class="section">
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
                                <option value="{{ $part->id }}">{{ $part->Part_name }} - {{ $part->Part_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_category" class="form-label">Kategori</label>
                        <select name="id_category" id="id_category" class="form-select select2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="plan_stock" class="form-label">Plan Stok</label>
                        <input type="text" name="plan_stock" id="plan_stock" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="OK">OK</option>
                            <option value="NG">NG</option>
                        </select>
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
