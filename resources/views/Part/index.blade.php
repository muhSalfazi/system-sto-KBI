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
    {{-- =====================alert ======================== --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
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

    @if (session('import_logs'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Detail Import:</strong>
            <ul>
                @foreach (session('import_logs') as $log)
                    <li>{{ $log }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ============================= --}}
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daftar Part</h5>
                        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                            <div class="mb-2 d-flex align-items-center">
                                <a href="{{ route('parts.create') }}" class="btn btn-primary btn-sm me-2">
                                    <i class="bi bi-plus-square"></i> Create Part
                                </a>
                                <button type="button" class="btn btn-success btn-sm  me-2"
                                    data-bs-toggle="modal"data-bs-target="#importModal">
                                 <i class="bi bi-file-earmark-spreadsheet-fill"></i> Import Excel
                                </button>
                            </div>
                            <div class="mb-3">
                                <form action="{{ route('parts.index') }}" method="GET" class="d-flex align-items-center">
                                    <select name="category_id" id="category_id" class="form-select form-select-sm me-2"
                                        style="width: 300px;">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm me-2"
                                        style="font-size: 0.875rem; padding: 4px 8px;">Filter</button>
                                    <a href="{{ route('parts.index') }}" class="btn btn-secondary btn-sm"
                                        style="font-size: 0.875rem; padding: 4px 8px;">Reset</a>
                                </form>
                            </div>

                        @endif
                        <div class="table-responsive animate__animated animate__fadeInUp">
                            <table class="table table-striped table-bordered datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Inv ID</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Part Number</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Type Package</th>
                                        <th class="text-center">Qty/Box</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Area</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parts as $part)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $part->Inv_id }}</td>
                                            <td class="text-center">{{ $part->Part_name }}</td>
                                            <td class="text-center">{{ $part->Part_number }}</td>
                                            <td class="text-center">{{ $part->category->name ?? '-' }}</td>
                                            <td class="text-center">{{ $part->customer->username ?? '-' }}</td>
                                            <td class="text-center">{{ $part->package->type_pkg ?? '-' }}</td>
                                            <td class="text-center">{{ $part->package->qty ?? '-' }}</td>
                                            <td class="text-center">{{ $part->plant->name ?? '-' }}</td>
                                            <td class="text-center">{{ $part->area->nama_area ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('parts.edit', $part->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    style="font-size: 0.875rem; padding: 4px 8px;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('parts.destroy', $part->id) }}" method="POST"
                                                    style="font-size: 0.875rem; padding: 4px 8px;"
                                                    onsubmit="return confirm('Are you sure you want to delete this part?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash3"></i>
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
    {{-- modal import Excel --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Parts from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('parts.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload Excel File</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xls,.xlsx">
                                 <small class="text-danger">*Download Template Excel Import: <a href="{{ asset('file/format-import-part(system-sto).xlsx') }}" download><i class="bi bi-download"></i> klik di sini</a></small>
                        </div>
                        <small class="text-info">
                            *Lihat referensi untuk Customer:
                            <a href="{{ asset('file/customer-reference.xlsx') }}" download>
                                <i class="bi bi-download"></i> Reference
                            </a>
                        </small>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success mt-1">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end --}}

@endsection
