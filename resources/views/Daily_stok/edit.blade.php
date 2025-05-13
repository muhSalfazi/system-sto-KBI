@extends('layouts.app')

@section('title', 'Update Daily Stok')

@section('content')
    <div class="pagetitle">
        <h1>Update Daily Stok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('daily-stock.index') }}">Daily Stok</a></li>
                <li class="breadcrumb-item active">Update</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        {{-- ==========alert=========== --}}
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
        {{-- ========================== --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Update Daily Stok</h5>
                <!-- Custom Styled Validation -->
                <form class="row g-3 needs-validation" novalidate enctype="multipart/form-data" method="POST"
                    action="{{ route('reports.update', $report->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Part Name -->
                    <div class="col-md-6">
                        <label for="part-name" class="form-label">Part Name</label>
                        <input type="text" id="part-name" name="part_name"
                            class="form-control @error('part_name') is-invalid @enderror" placeholder="Enter part name"
                            value="{{ old('part_name', $report->inventory->part->Part_name ?? '') }}" disabled>
                        @error('part_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Part Number -->
                    <div class="col-md-6">
                        <label for="part-number" class="form-label">Part Number</label>
                        <input type="text" id="part-number" name="part_number"
                            class="form-control @error('part_number') is-invalid @enderror" placeholder="Enter part number"
                            value="{{ old('part_number', $report->inventory->part->Part_number ?? '') }}" disabled>
                        @error('part_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Inventory Code and Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inventory-code" class="form-label">Inventory Code</label>
                            <input required type="text" id="inventory-code" name="inventory_id"
                                class="form-control @error('inventory_id') is-invalid @enderror"
                                placeholder="Enter inventory code"
                                value="{{ old('inventory_id', $report->inventory->part->Inv_id ?? '') }}" disabled>
                            @error('inventory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="OK" {{ old('status', $report->status ?? '') == 'OK' ? 'selected' : '' }}>
                                    OK</option>
                                <option value="NG"
                                    {{ old('status', $report->status ?? '') == 'NG' ? 'selected' : '' }}>NG</option>
                                <option value="VIRGIN"
                                    {{ old('status', $report->status ?? '') == 'VIRGIN' ? 'selected' : '' }}>VIRGIN
                                </option>
                                <option value="FUNSAI"
                                    {{ old('status', $report->status ?? '') == 'FUNSAI' ? 'selected' : '' }}>FUNSAI
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Quantity Details -->
                    <div class="col-12 border rounded p-3">
                        <h6 class="text-center">Quantity Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="qty_per_box" class="form-label">Qty/Box</label>
                                <input type="number" id="qty_per_box" name="qty_per_box" class="form-control" required
                                    placeholder="Enter quantity per box"
                                    value="{{ old('qty_per_box', $report->qty_per_box ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="qty_box" class="form-label">Qty Box</label>
                                <input type="number" id="qty_box" name="qty_box" class="form-control" required
                                    value="{{ old('qty_box', $report->qty_box ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="number" id="total" name="total" class="form-control"
                                    value="{{ old('total', $report->total ?? '') }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total</label>
                                <input type="number" id="grand_total" name="grand_total" class="form-control" required
                                    value="{{ old('grand_total', $report->grand_total ?? '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <input type="number" id="qty_per_box_2" name="qty_per_box_2" class="form-control"
                                    placeholder="Enter quantity per box"
                                    value="{{ old('qty_per_box_2', $report->qty_per_box_2 ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="qty_box_2" name="qty_box_2" class="form-control"
                                    value="{{ old('qty_box_2', $report->qty_box_2 ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="total_2" name="total_2" class="form-control"
                                    value="{{ old('total_2', $report->total_2 ?? '') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    <script>
        function calculateTotals() {
            let qtyPerBox = parseFloat(document.getElementById("qty_per_box").value) || 0;
            let qtyBox = parseFloat(document.getElementById("qty_box").value) || 0;
            let qtyPerBox2 = parseFloat(document.getElementById("qty_per_box_2").value) || 0;
            let qtyBox2 = parseFloat(document.getElementById("qty_box_2").value) || 0;

            // Calculate totals
            let oldTotal = qtyPerBox2 * qtyBox2;
            let total = qtyPerBox * qtyBox;
            let grandTotal = oldTotal + total;

            // Update the input fields
            document.getElementById("total_2").value = oldTotal;
            document.getElementById("total").value = total;
            document.getElementById("grand_total").value = grandTotal;
        }

        // Attach event listeners to inputs
        let inputs = document.querySelectorAll("#qty_per_box_2, #qty_box_2, #qty_per_box, #qty_box");
        inputs.forEach(input => {
            input.addEventListener("input", calculateTotals);
        });
    </script>
@endsection
