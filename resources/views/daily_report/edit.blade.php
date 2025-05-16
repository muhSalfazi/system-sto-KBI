@extends('layouts.user')

@section('contents')
    <div class="container">
        <div class="card mt-4 shadow-lg">
            <div class="card-body p-4">
                <h4><strong>PT Kyoraku Blowmolding Indonesia</strong></h4>
                <p class="text-sm"><strong>PPIC Department / Warehouse Section</strong></p>
                <div class="text-center mb-4">
                    <h4>Edit STO Report #{{ $log->id }}</h4>
                </div>

                <form method="POST" action="{{ route('sto.updateLog', $log->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Part info -->
                    <div class="mb-3">
                        <label class="form-label">Part Name</label>
                        <input type="text" class="form-control" value="{{ $log->inventory->part->Part_name ?? '-' }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Part Number</label>
                        <input type="text" class="form-control" value="{{ $log->inventory->part->Part_number ?? '-' }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control"
                            value="{{ $log->inventory->part->category->name ?? '-' }}" readonly>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Plant</label>
                            <input type="text" class="form-control"
                                value="{{ $log->inventory->part->plant->name ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Area</label>
                            <input type="text" class="form-control"
                                value="{{ $log->inventory->part->area->nama_area ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Rak</label>
                            <input type="text" class="form-control"
                                value="{{ $log->inventory->part->rak->nama_rak ?? '-' }}" readonly>
                        </div>
                    </div>

                    <!-- Plan stock -->
                    <div class="mb-3">
                        <label class="form-label">Plan Stock</label>
                        <input type="number" name="plan_stock" class="form-control"
                            value="{{ $log->inventory->plan_stock }}">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" {{ $log->status == $s ? 'selected' : '' }}>
                                    {{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input complete & uncomplete -->
                    <div class="p-3 border rounded mb-3">
                        <h6><strong>Item Complete</strong></h6>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Qty/Box</label>
                                <input type="number" name="qty_per_box" class="form-control"
                                    value="{{ $log->boxComplete->qty_per_box ?? 0 }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Qty Box</label>
                                <input type="number" name="qty_box" class="form-control"
                                    value="{{ $log->boxComplete->qty_box ?? 0 }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Total</label>
                                <input type="number" name="total" class="form-control"
                                    value="{{ $log->boxComplete->total ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Grand Total</label>
                                <input type="number" name="grand_total" class="form-control"
                                    value="{{ $log->Total_qty ?? 0 }}" readonly>
                            </div>
                        </div>
                          <button type="button" class="btn btn-outline-primary mt-2 col-md-12"
                            onclick="toggleOptionalQuantityInputs()">+ Item Uncomplete</button>

                        <div class="row mt-3" id="optionalQuantityInputs" style="display: none;">
                            {{-- @if ($log->boxUncomplete) --}}
                            <h6 class="mt-2">Item Uncomplete</h6>
                            <div class="col-md-3 mb-2">
                                <label>Qty/Box</label>
                                <input type="number" name="qty_per_box_2" class="form-control"
                                    value="{{ $log->boxUncomplete->qty_per_box ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Qty Box</label>
                                <input type="number" name="qty_box_2" class="form-control"
                                    value="{{ $log->boxUncomplete->qty_box ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Total</label>
                                <input type="number" name="total_2" class="form-control"
                                    value="{{ $log->boxUncomplete->total ?? '' }}" readonly>
                            </div>
                        </div>

                        {{-- @endif --}}
                    </div>

                    <!-- Submit -->
                    <input type="hidden" name="prepared_by" value="{{ auth()->id() }}">
                    <input type="hidden" name="issued_date" value="{{ date('Y-m-d') }}">

                    <button type="submit" class="btn btn-success w-100">Update STO</button>
                    <a href="{{ route('dailyreport.index') }}" class="btn btn-info mt-3 col-12">Back</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('inv_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('part_name').value = selected.dataset.part || '';
            document.getElementById('part_number').value = selected.dataset.number || '';
            document.getElementById('category').value = selected.dataset.category || '';
            document.getElementById('plant').value = selected.dataset.plant || '';
            document.getElementById('area').value = selected.dataset.area || '';
            document.getElementById('rak').value = selected.dataset.rak || '';
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function calculateTotals() {
                let QtyPerBox2 = parseFloat(document.getElementById("qty_per_box_2").value) || 0;
                let QtyBox2 = parseFloat(document.getElementById("qty_box_2").value) || 0;
                let qtyPerBox = parseFloat(document.getElementById("qty_per_box").value) || 0;
                let qtyBox = parseFloat(document.getElementById("qty_box").value) || 0;

                // Calculate totals
                let Total2 = QtyPerBox2 * QtyBox2;
                let total = qtyPerBox * qtyBox;
                let grandTotal = Total2 + total;

                // Update the input fields
                document.getElementById("total_2").value = Total2;
                document.getElementById("total").value = total;
                document.getElementById("grand_total").value = grandTotal;
            }

            // Attach event listeners to inputs
            let inputs = document.querySelectorAll("#qty_per_box_2, #qty_box_2, #qty_per_box, #qty_box");
            inputs.forEach(input => {
                input.addEventListener("input", calculateTotals);
            });
            document.getElementById('loader').style.display = 'none';
        });

        function toggleOptionalQuantityInputs() {
            $('#optionalQuantityInputs').toggle();
        }
    </script>
@endsection
