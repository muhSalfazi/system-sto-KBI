@extends('layouts.app')

@section('title', 'Data Parts')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Data Parts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Data Parts</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif

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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daftar Part</h5>
                        <div class="mb-3">
                            <a href="{{ route('parts.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-square"></i> Tambah Part
                            </a>
                        </div>

                        <div class="table-responsive animate__animated animate__fadeInUp">
                            <table class="table table-striped table-bordered datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Inv ID</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Part Number</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Type Package</th>
                                        <th class="text-center">Qty Package</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Area</th>
                                        <th class="text-center">Rak</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parts as $part)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $part->inv_id }}</td>
                                            <td class="text-center">{{ $part->part_name }}</td>
                                            <td class="text-center">{{ $part->part_number }}</td>
                                            <td class="text-center">{{ $part->customer->username ?? '-' }}</td>
                                            <td class="text-center">{{ $part->package->type_pkg ?? '-' }}</td>
                                            <td class="text-center">{{ $part->package->qty ?? '-' }}</td>
                                            <td class="text-center">{{ $part->plant->name ?? '-' }}</td>
                                            <td class="text-center">{{ $part->area->label ?? '-' }}</td>
                                            <td class="text-center">{{ $part->rak->nama_rak ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('parts.edit', $part->id) }}" class="btn btn-success btn-sm">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <form action="{{ route('parts.destroy', $part->id) }}" method="POST"
                                                      style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus part ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash3"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
