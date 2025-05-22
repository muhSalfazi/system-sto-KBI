@extends('layouts.app')

@section('title', 'Create Part')

@section('content')
    <div class="pagetitle">
        <h1>Create Part</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sto.index') }}">Data Part</a></li>
                <li class="breadcrumb-item active">Create Part</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- =========alert ======= --}}
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
        {{-- ======================== --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Input Part</h5>
                <form method="POST" action="{{ route('parts.store') }}" class="row g-3 needs-validation" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="Inv_id" class="form-label">Inventory ID</label>
                        <input type="text" name="Inv_id" id="inv_id"
                            class="form-control @error('Inv_id') is-invalid @enderror" value="{{ old('Inv_id') }}" required>
                        @error('Inv_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="Part_name" class="form-label">Part Name</label>
                        <input type="text" name="Part_name" id="Part_name"
                            class="form-control @error('Part_name') is-invalid @enderror" value="{{ old('Part_name') }}"
                            required>
                        @error('Part_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="Part_number" class="form-label">Part Number</label>
                        <input type="text" name="Part_number" id="Part_number"
                            class="form-control @error('Part_number') is-invalid @enderror" value="{{ old('Part_number') }}"
                            required>
                        @error('Part_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="id_category" class="form-label">Category</label>
                        <select name="id_category" id="id_category"
                            class="form-select @error('id_category') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('id_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="id_customer" class="form-label">Customer</label>
                        <select name="id_customer" id="id_customer"
                            class="form-select select2 @error('id_customer') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->username }}</option>
                            @endforeach
                        </select>
                        @error('id_customer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Plant, Area, Rak --}}
                    <div class="col-md-4">
                        <label for="id_plan" class="form-label">Plant</label>
                        <select name="id_plan" id="id_plan" class="form-select" required>
                            <option value="">Pilih Plant</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->id }}">{{ $plant->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger">*Pilih Plant sebelum Area dan Rak</small>
                    </div>

                    <div class="col-md-4">
                        <label for="nama_area" class="form-label">Area</label>
                        <input type="text" name="nama_area" id="nama_area"
                            class="form-control @error('nama_area') is-invalid @enderror" value="{{ old('nama_area') }}"
                            required>
                    </div>

                    <div class="col-md-4">
                        <label for="nama_rak" class="form-label">Rak</label>
                        <input type="text" name="nama_rak" id="nama_rak"
                            class="form-control @error('nama_rak') is-invalid @enderror" value="{{ old('nama_rak') }}"
                            required>
                    </div>
                    {{-- end --}}
                    {{-- Package --}}
                    <div class="col-md-6">
                        <label for="type_pkg" class="form-label">Type Package</label>
                        <input type="text" name="type_pkg" id="type_pkg"
                            class="form-control @error('type_pkg') is-invalid @enderror" value="{{ old('type_pkg') }}"
                            required>
                        @error('type_pkg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="qty" class="form-label">Qty/Kanban</label>
                        <input type="number" name="qty" id="qty"
                            class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty') }}" required>
                        @error('qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Simpan Part</button>
                        <a href="{{ route('parts.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

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

@endsection
