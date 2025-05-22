@extends('layouts.app')

@section('title', 'Forecast Data')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Forecast Data</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Forecast Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- =================alert ================ --}}
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
            <strong>Detail Forecast:</strong>
            <ul>
                @foreach (session('import_logs') as $log)
                    <li>{{ $log }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ============================ --}}

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title animate__animated animate__fadeInLeft">Forecast Data</h5>
                        <div class="mb-2">
                            @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                <a href="{{ route('forecast.create') }}" class="btn btn-primary btn-sm  mb-1">
                                    <i class="bi bi-plus-square"></i> Create Forecast
                                </a>
                                <button type="button" class="btn btn-success btn-sm  mb-1 " data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Import Excel
                                </button>
                            @endif
                        </div>
                        {{-- filter --}}
                        <form method="GET" class="row g-3 align-items-end mb-3">
                            <div class="col-md-4">
                                <label for="customer" class="form-label">Filter Customer</label>
                                <select name="customer" id="customer" class="form-select select2">
                                    <option value="">-- Semua Customer --</option>
                                    @foreach ($customers as $cust)
                                        <option value="{{ $cust->username }}"
                                            {{ request('customer') == $cust->username ? 'selected' : '' }}>
                                            {{ $cust->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="forecast_month" class="form-label">Filter Forecast Bulan</label>
                                <input type="month" name="forecast_month" class="form-control" placeholder="mm-yyyy"
                                    value="{{ request('forecast_month') }}">
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm"
                                    style="font-size: 0.875rem; padding: 4px 8px;">Filter</button>
                                <a href="{{ route('forecast.index') }}" class="btn btn-secondary btn-sm"
                                    style="font-size: 0.875rem; padding: 4px 8px;">Reset</a>
                            </div>
                        </form>
                        {{-- end filter --}}

                        <div class="table-responsive animate__animated animate__fadeInUp">
                            <table class="table table-striped table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Inv ID</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Part Number</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">forecast month</th>
                                        <th class="text-center">Working Days</th>
                                        <th class="text-center">Po/Pcs</th>
                                        <th class="text-center">Min</th>
                                        <th class="text-center">Max</th>
                                        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                            <th class="text-center">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forecasts as $index => $forecast)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $forecast->part->Inv_id ?? '-' }}</td>
                                            <td class="text-center">{{ $forecast->part->Part_name ?? '-' }}</td>
                                            <td class="text-center">{{ $forecast->part->Part_number ?? '-' }}</td>
                                            <td class="text-center">{{ $forecast->part->plant->name ?? '-' }}</td>
                                            <td class="text-center">{{ $forecast->part->customer->username ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($forecast->forecast_month)->format('M Y') }}
                                            </td>

                                            <td class="text-center">{{ $forecast->hari_kerja }}</td>
                                            <td class="text-center">{{ $forecast->PO_pcs }}</td>
                                            <td class="text-center">{{ $forecast->min }}</td>
                                            <td class="text-center">{{ $forecast->max }}</td>
                                            @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                                <td class="text-center">

                                                    <a href="{{ route('forecast.edit', $forecast->id) }}"
                                                        class="btn btn-success btn-sm"
                                                        style="font-size: 0.875rem; padding: 4px 8px;">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('forecast.destroy', $forecast->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this forecast?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mt-1"
                                                            style="font-size: 0.875rem; padding: 4px 8px;">
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

    {{-- modal import Excel --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Forecast from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('forecast.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload Excel File</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xls,.xlsx">
                            <small class="text-danger">*Download Template Excel Import: <a
                                    href="{{ asset('file/format-import-Forecast(system-sto).xlsx') }}" download> <i
                                        class="bi bi-download"></i> klik di sini</a></small>
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
