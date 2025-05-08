@extends('layouts.app')

@section('title', 'Edit LIST STO')

@section('content')
<div class="pagetitle">
    <h1>Edit STO</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sto.index') }}">Data STO</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Form Edit STO</h5>
            <form action="{{ route('sto.update', $sto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_part" class="form-label">Part</label>
                    <select name="id_part" id="id_part" class="form-select select2" required>
                        <option value="">-- Pilih Part --</option>
                        @foreach ($parts as $part)
                            <option value="{{ $part->id }}"
                                {{ $part->id == $sto->id_part ? 'selected' : '' }}>
                                {{ $part->Part_name }} - {{ $part->Part_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_category" class="form-label">Kategori</label>
                    <select name="id_category" id="id_category" class="form-select select2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $sto->id_category ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
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

{{-- Include Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Pilih --",
            width: '100%'
        });
    });
</script>
@endsection
