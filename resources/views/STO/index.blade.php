@extends('layouts.app')

@section('title', 'List Sto')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Data Sto</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">List Data Sto</li>
            </ol>
        </nav>
    </div>
    {{-- ========================= alert ======================= --}}
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

    @if (session('import_logs'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul>
                <strong>Detail Import:</strong>
                <ul>
                    @foreach (session('import_logs') as $log)
                        <li>{{ $log }}</li>
                    @endforeach
                </ul>
            </ul>
        </div>
    @endif
    {{-- ==================================== --}}
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daftar STO</h5>
                        <div class="d-flex align-items-center mb-3">
                                @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                <a href="{{ route('sto.create.get') }}" class="btn btn-primary btn-sm me-2"
                                    style="font-size: 0.875rem; padding: 4px 8px;">
                                    <i class="bi bi-plus-square"></i> Create STO
                                </a>
                                <button type="button" class="btn btn-success btn-sm me-2"
                                    style="font-size: 0.875rem; padding: 4px 8px;" data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    <i class="bi bi-filetype-csv"></i></i> Import Excel By Ledger
                                </button>
                        @endif
                        <button type="button" class="btn btn-warning btn-sm me-2"
                            style="font-size: 0.875rem; padding: 4px 8px;"
                            onclick="window.location='{{ route('sto.export', ['category_id' => request('category_id')]) }}'">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export Excel
                        </button>

                    </div>
                    <div class="table-responsive animate__animated animate__fadeInUp">
                        <div class="d-flex align-items-center mb-3">
                            <!-- Filter Kategori -->
                            <form action="{{ route('sto.index') }}" method="GET" class="d-flex gap-2 align-items-end">
                                <!-- Filter Kategori -->
                                <div>
                                    <label for="category_id" class="form-label mb-0">Kategori</label>
                                    <select name="category_id" id="category_id" class="form-select form-select-sm "
                                        style="width: 200px;">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Remark -->
                                <div>
                                    <label for="remark" class="form-label mb-0">Remark</label>
                                    <select name="remark" id="remark" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">-- Pilih Remark --</option>
                                        <option value="normal" {{ request('remark') == 'normal' ? 'selected' : '' }}>
                                            Normal
                                        </option>
                                        <option value="abnormal" {{ request('remark') == 'abnormal' ? 'selected' : '' }}>
                                            Abnormal</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm me-2"
                                    style="font-size: 0.875rem; padding: 4px 8px;">Filter</button>
                                <a href="{{ route('sto.index') }}" class="btn btn-secondary btn-sm me-2"
                                    style="font-size: 0.875rem; padding: 4px 8px;">Reset</a>
                            </form>

                        </div>
                    </div>
                    <div class="table-responsive animate__animated animate__fadeInUp">
                        <table class="table table-striped table-bordered datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">DateTime</th>
                                    <th class="text-center">Inv ID</th>
                                    <th class="text-center">Part Name</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">Plan Stok</th>
                                    <th class="text-center">Act Stok</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">STO Period</th>
                                    <th class="text-center">Remark</th>
                                    <th class="text-center">Note-Remark</th>
                                    @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                        <th class="text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parts as $part)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ $part->updated_at ? $part->updated_at->format('d-m-Y H:i:s') : '-' }}
                                        </td>
                                        <td class="text-center">{{ $part->part->Inv_id ?? '-' }}</td>
                                        <td class="text-center">{{ $part->part->Part_name ?? '-' }}</td>
                                        <td class="text-center">{{ $part->part->Part_number ?? '-' }}</td>
                                        <td class="text-center">{{ $part->plan_stock ?? '-' }}</td>
                                        <td class="text-center">{{ $part->act_stock ?? '-' }}</td>
                                        <td class="text-center">{{ $part->part->category->name ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $part->updated_at ? $part->updated_at->format('M Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($part->remark === 'normal')
                                                <span class="badge bg-success text-white px-2 py-1">Normal</span>
                                            @elseif ($part->remark === 'abnormal')
                                                <span class="badge bg-danger text-white px-2 py-1">Abnormal</span>
                                            @else
                                                <span class="badge bg-secondary text-white px-2 py-1">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center">{{ $part->note_remark ?? '-' }}</td>
                                        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                            <td class="text-center">
                                                <a href="{{ route('sto.edit', $part->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    style="font-size: 0.875rem; padding: 4px 8px;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('sto.destroy', $part->id) }}" method="POST"
                                                    style="font-size: 0.875rem; padding: 4px 8px;"
                                                    onsubmit="return confirm('Are you sure you want to delete this STO?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
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


    {{-- modal import excel --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import List Sto from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sto.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload Excel File</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xls,.xlsx">
                                  <small class="text-danger">*Download Template Excel Import: <a href="{{ asset('file/format-import-listSto(system-sto).xlsx') }}" download><i class="bi bi-download"></i> klik di sini</a></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end --}}
@endsection
