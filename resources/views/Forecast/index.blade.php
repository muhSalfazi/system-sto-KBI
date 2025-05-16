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
                        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                            <div class="mb-2">
                                <a href="{{ route('forecast.create') }}" class="btn btn-primary btn-sm  mb-1">
                                    <i class="bi bi-plus-square"></i> Create Forecast
                                </a>
                                <button type="button" class="btn btn-success btn-sm  mb-1 " data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    <i class="bi bi-filetype-csv"></i> Import Csv
                                </button>
                                {{-- convert --}}
                                <button type="button" class="btn btn-info btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#exceltocsv">
                                    <i class="bi bi-file-earmark-excel"></i>Convert Excel to Csv
                                </button>
                            </div>
                        @endif
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
                                        <th class="text-center">Qty/Box</th>
                                        <th class="text-center">Working Days</th>
                                        <th class="text-center">Min</th>
                                        <th class="text-center">Max</th>
                                        <th class="text-center">Action</th>
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
                                            <td class="text-center">
                                                {{ $forecast->part->customer->username ?? '-' }}</td>
                                            <td class="text-center">{{ $forecast->Qty_Box }}</td>
                                            <td class="text-center">{{ $forecast->hari_kerja }}</td>
                                            <td class="text-center">{{ $forecast->min }}</td>
                                            <td class="text-center">{{ $forecast->max }}</td>
                                            <td class="text-center">

                                                <a href="{{ route('forecast.edit', $forecast->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    style="font-size: 0.875rem; padding: 4px 8px;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('forecast.destroy', $forecast->id) }}"
                                                    method="POST" style="font-size: 0.875rem; padding: 4px 8px;"
                                                    onsubmit="return confirm('Are you sure you want to delete this forecast?')">
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

    {{-- modal import Csv --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Forecast from Csv</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('forecast.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload Csv File</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".csv">
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
