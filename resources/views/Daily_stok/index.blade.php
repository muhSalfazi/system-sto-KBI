@extends('layouts.app')

@section('title', 'Daily Stock')

@section('content')
    <div class="pagetitle animate__animated animate__fadeInLeft">
        <h1>Daily Stok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"> Daily Stok</li>
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
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daily Stock Logs</h5>
                        <div class="mb-2">
                            <button type="button" class="btn btn-success btn-sm  mb-1 "
                                data-bs-toggle="modal"data-bs-target="#importModal">
                                <i class="bi bi-filetype-csv"></i> Import Csv
                            </button>
                            {{-- convert  --}}
                            <button type="button" class="btn btn-info btn-sm "
                                data-bs-toggle="modal"data-bs-target="#exceltocsv">
                                <i class="bi bi-file-earmark-excel"></i>Convert Excel to Csv
                            </button>

                            {{-- export excel --}}
                            <button type="button" class="btn btn-warning btn-sm"
                                onclick="window.location='{{ route('sto.export', ['category_id' => request('category_id')]) }}'">
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export Excel
                            </button>

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
                                        <th class="text-center">Prepared By</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyStockLogs as $key => $log)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $log->inventory->id }}</td>
                                            <td class="text-center">{{ $log->inventory->part->Part_name }}</td>
                                            <td class="text-center">{{ $log->inventory->part->Part_number }}</td>
                                            <td class="text-center">{{ $log->Total_qty }}</td>
                                            <td class="text-center">{{ $log->inventory->part->customer->name }}</td>
                                            <td class="text-center">{{ $log->user->name }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
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
