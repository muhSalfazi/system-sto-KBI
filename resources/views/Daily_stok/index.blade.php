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
                        <h5 class="card-title animate__animated animate__fadeInLeft">Daily Stock By Barcode</h5>
                        <div class="mb-2">

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
                            <table class="table table-striped table-bordered datatable mt-2">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">No</th>
                                        <th class="text-center align-middle" rowspan="2">DateTime</th>
                                        <th class="text-center align-middle" rowspan="2">Inv Id</th>
                                        <th class="text-center align-middle" rowspan="2">Part Name</th>
                                        <th class="text-center align-middle" rowspan="2">Part No</th>
                                        <th class="text-center" colspan="2">Sto Stock Pcs</th>
                                        <th class="text-center" colspan="2">Act Stock</th>
                                        <th class="text-center align-middle" rowspan="2">Customer</th>
                                        <th class="text-center align-middle" rowspan="2">Status</th>
                                        <th class="text-center align-middle" rowspan="2">Prepared By</th>
                                        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                            <th class="text-center align-middle" rowspan="2">Action</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="text-center">Min</th>
                                        <th class="text-center">Max</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyStockLogs as $key => $log)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $log->created_at }}</td>
                                            <td>{{ optional(optional($log->inventory)->part)->Inv_id ?? '-' }}</td>
                                            <td>{{ optional(optional($log->inventory)->part)->Part_name ?? '-' }}</td>
                                            <td>{{ optional(optional($log->inventory)->part)->Part_number ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ $log->forecast_min ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $log->forecast_max ?? '-' }}
                                            </td>

                                            <td class="text-center">{{ $log->Total_qty }}</td>
                                            <td class="text-center">{{ $log->stock_per_day }}</td>
                                            <td>{{ optional(optional(optional($log->inventory)->part)->customer)->username ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ $log->status }}</td>
                                            <td class="text-center">{{ $log->user->username }}</td>
                                            @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
                                                <td class="text-center">
                                                    <form action="{{ route('reports.destroy', $log->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            style="font-size: 0.875rem; padding: 4px 8px;">
                                                            <i class="bi bi-trash"></i>
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
 <script>
    $(document).ready(function() {
        $('.datatable').DataTable();
    });
</script>
@endsection
