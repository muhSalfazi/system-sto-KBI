@extends('layouts.app')

@section('title', 'Edit STO List')

@section('content')
    <div class="pagetitle">
        <h1>Edit STO List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sto.index') }}">List Data Sto</a></li>
                <li class="breadcrumb-item active">Edit STO List</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- ==========alert ========= --}}
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
        {{-- ========================= --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Edit STO</h5>
                <form action="{{ route('sto.update', $sto->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- HTTP Method untuk Update -->

                    <div class="mb-3">
                        <label for="id_part" class="form-label">Part</label>
                        <select name="id_part" id="id_part" class="form-select select2" disabled>
                            <option value="{{ $sto->part->id }}" selected>
                                {{ $sto->part->Part_name }} - {{ $sto->part->Part_number }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_category" class="form-label">Kategori</label>
                        <select name="id_category" id="id_category" class="form-select select2">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $sto->id_category == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="plan_stock" class="form-label">Plan Stok</label>
                        <input type="text" name="plan_stock" id="plan_stock" class="form-control"
                            value="{{ $sto->plan_stock }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="OK" {{ $sto->status == 'OK' ? 'selected' : '' }}>OK</option>
                            <option value="NG" {{ $sto->status == 'NG' ? 'selected' : '' }}>NG</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
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
