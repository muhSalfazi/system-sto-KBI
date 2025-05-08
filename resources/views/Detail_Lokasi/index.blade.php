@extends('layouts.app')

@section('title', 'Data Detail Lokasi')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Data Detail Lokasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Data Per Rak/Area/Plan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
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
                        <h5 class="card-title animate__animated animate__fadeInLeft">Detail Lokasi</h5>
                        <div class="mb-2">
                            <a href="{{ route('create.detail-lokasi') }}" class="btn btn-primary btn-sm"><i
                                    class="bi bi-plus-square"></i> Create Detail Lokasi</a>
                        </div>
                        <div class="table-responsive animate__animated animate__fadeInUp">
                            <table class="table table-striped table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Rak</th>
                                        <th class="text-center">Nama Area</th>
                                        <th class="text-center">Nama Plan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($raks as $index => $rak)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $rak->nama_rak }}</td>
                                            <td class="text-center">{{ $rak->area->nama_area ?? '-' }}</td>
                                            <td class="text-center">{{ $rak->area->plant->name ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('edit.detail-lokasi', $rak->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    style="font-size: 0.875rem; padding: 4px 8px;">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <form action="{{ route('destroy.detail-lokasi', $rak->id) }}" method="POST"
                                                    style="font-size: 0.875rem; padding: 4px 8px;"
                                                    onsubmit="return confirm('Yakin ingin menghapus part ini?')">
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
