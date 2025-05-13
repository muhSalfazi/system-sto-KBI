@extends('layouts.app')

@section('title', 'Daily Stock')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Daily Stok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Daily Stok</li>
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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daily Stock Logs</h5>
                        <div class="mb-2">
                            <button type="button" class="btn btn-success btn-sm  mb-1 " data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="bi bi-filetype-csv"></i> Import Csv
                            </button>
                            {{-- convert --}}
                            <button type="button" class="btn btn-info btn-sm " data-bs-toggle="modal"
                                data-bs-target="#exceltocsv">
                                <i class="bi bi-file-earmark-excel"></i>Convert Excel to Csv
                            </button>

                            {{-- export excel --}}
                            <a href="{{ route('daily-stock.export', ['status' => request('status')]) }}"
                                class="btn btn-warning btn-sm">
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export Excel
                            </a>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('daily-stock.index') }}">
                                        <label for="statusFilter" class="form-label">Filter Status:</label>
                                        <select class="form-select" name="status" id="statusFilter"
                                            onchange="this.form.submit()">
                                            <option value="">-- Semua Status --</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}"
                                                    {{ request('status') == $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive animate__animated animate__fadeInUp">
                            <table class="table table-striped table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Inv Id</th>
                                        <th class="text-center">Part Name</th>
                                        <th class="text-center">Part No</th>
                                        <th class="text-center">Total Qty</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Prepared By</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyStockLogs as $key => $log)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $log->inventory->part->Inv_id }}</td>
                                            <td class="text-center">{{ $log->inventory->part->Part_name }}</td>
                                            <td class="text-center">{{ $log->inventory->part->Part_number }}</td>
                                            <td class="text-center">{{ $log->Total_qty }}</td>
                                            <td class="text-center">{{ $log->inventory->part->customer->username }}</td>
                                            <td class="text-center">{{ $log->status }}</td>
                                            <td class="text-center">{{ $log->user->username }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('reports.edit', $log->id) }}"
                                                    class="btn btn-warning btn-sm mb-1"
                                                    style="font-size: 0.875rem; padding: 4px 8px;">Update</a>
                                                <form action="{{ route('reports.destroy', $log->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        style="font-size: 0.875rem; padding: 4px 8px;">
                                                        <i class="bi bi-trash"></i> Delete
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
                    <h5 class="modal-title" id="importModalLabel">Import Daily from Csv</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('daily-stock.import.process') }}" method="POST" enctype="multipart/form-data">
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
