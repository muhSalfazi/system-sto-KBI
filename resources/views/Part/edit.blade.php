@extends('layouts.app')

@section('title', 'Edit Part')

@section('content')
    <div class="pagetitle">
        <h1>Edit Part</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('parts.index') }}">Data Part</a></li>
                <li class="breadcrumb-item active">Edit Part</li>
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
                <h5 class="card-title">Form Edit Part</h5>
                <form method="POST" action="{{ route('parts.update', $part->id) }}" class="row g-3 needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <label for="Inv_id" class="form-label">Inventory ID</label>
                        <input type="text" name="Inv_id" id="Inv_id" class="form-control"
                            value="{{ old('Inv_id', $part->Inv_id) }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="Part_name" class="form-label">Part Name</label>
                        <input type="text" name="Part_name" id="part_name" class="form-control"
                            value="{{ old('Part_name', $part->Part_name) }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="Part_number" class="form-label">Part Number</label>
                        <input type="text" name="Part_number" id="part_number" class="form-control"
                            value="{{ old('Part_number', $part->Part_number) }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label for="id_category" class="form-label">Category</label>
                        <input type="text" name="id_category" id="id_category" class="form-control"
                            value="{{ old('id_category', $part->category->name) }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="id_customer" class="form-label">Customer</label>
                        <select name="id_customer" id="id_customer" class="form-select" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $part->id_customer == $customer->id ? 'selected' : '' }}>{{ $customer->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="id_plan" class="form-label">Plant</label>
                        <select name="id_plan" id="id_plan" class="form-select" required>
                            <option value="">Pilih Plant</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->id }}" {{ $part->id_plan == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger">*Pilih Plant sebelum Area</small>
                    </div>

                    <div class="col-md-6">
                        <label for="id_area" class="form-label">Area</label>
                        <select name="id_area" id="id_area" class="form-select" required>
                            <option value="{{ $part->id_area }}">{{ $part->area->nama_area ?? '-' }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="type_pkg" class="form-label">Type Package</label>
                        <input type="text" name="type_pkg" id="type_pkg" class="form-control"
                            value="{{ old('type_pkg', $part->package->type_pkg ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="qty" class="form-label">Qty Package</label>
                        <input type="number" name="qty" id="qty" class="form-control"
                            value="{{ old('qty', $part->package->qty ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Update Part</button>
                        <a href="{{ route('parts.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('id_plan');
            const areaSelect = document.getElementById('id_area');

            planSelect.addEventListener('change', function() {
                const plantId = this.value;
                areaSelect.innerHTML = '<option value="">Loading...</option>';

                if (plantId) {
                    fetch(`/get-areas/${plantId}`)
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">Pilih Area</option>';
                            data.forEach(area => {
                                options +=
                                    `<option value="${area.id}">${area.nama_area}</option>`;
                            });
                            areaSelect.innerHTML = options;
                        });
                }
            });

        });
    </script>
@endsection
