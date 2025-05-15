@extends('layouts.user')

@section('contents')
    <div class="container">
        <div class="card mt-4 shadow-lg">
            <div class="card-body p-4">
                <h5><strong>PT Kyoraku Blowmolding Indonesia</strong></h5>
                <p class="text-sm"><strong>PPIC Department / Warehouse Section</strong></p>
                <div class="text-center mb-4">
                    <h5>Inventory Entry Form</h5>
                </div>

                <form method="POST" action="{{ route('sto.storeNew') }}">
                    @csrf
                    <select name="inv_id" id="inv_id" class="form-select mb-2" required>
                        <option value="">-- Pilih Inv_id --</option>
                        @foreach ($parts as $part)
                            <option value="{{ $part->Inv_id }}" data-part="{{ $part->Part_name }}"
                                data-number="{{ $part->Part_number }}" data-category="{{ $part->category->name ?? '' }}"
                                data-plant="{{ $part->plant->name ?? '' }}" data-area="{{ $part->area->nama_area ?? '' }}">
                                {{ $part->Inv_id }} - {{ $part->Part_number }} - {{ $part->Part_name }}</option>
                        @endforeach
                    </select>
                    <!-- Part Name -->
                    <div class="mb-3">
                        <label class="form-label">Part Name</label>
                        <input type="text" id="part_name" class="form-control" readonly>
                    </div>

                    <!-- Part Number -->
                    <div class="mb-3">
                        <label class="form-label">Part Number</label>
                        <input type="text" id="part_number" class="form-control" readonly>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" id="category" class="form-control" readonly>
                    </div>

                    <!-- Plant -->
                    <div class="mb-3">
                        <label class="form-label">Plant</label>
                        <input type="text" id="plant" class="form-control" readonly>
                    </div>

                    <!-- Area -->
                    <div class="mb-3">
                        <label class="form-label">Area</label>
                        <input type="text" id="area" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan Stock</label>
                        <input type="number"name="plan_stock" id="plan_stock" class="form-control" >
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option disabled selected>-- Pilih Status --</option>
                            @foreach (['OK', 'NG', 'Virgin', 'Funsai'] as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Quantity Input -->
                    <div class="p-3 border rounded mb-3">
                        <h6><strong>Item Complete</strong></h6>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Qty/Box</label>
                                <input type="number" name="qty_per_box" id="qty_per_box" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Qty Box</label>
                                <input type="number" name="qty_box" id="qty_box" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Total</label>
                                <input type="number" name="total" id="total" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Grand Total</label>
                                <input type="number" name="grand_total" id="grand_total" class="form-control" readonly>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary mt-2 col-md-12"
                            onclick="toggleOptionalQuantityInputs()">+ Item Uncomplete</button>

                        <div id="optionalQuantityInputs" class="row mt-3" style="display:none;">
                            <div class="col-md-3 mb-2">
                                <label>Qty/Box</label>
                                <input type="number" name="qty_per_box_2" id="qty_per_box_2" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Qty Box</label>
                                <input type="number" name="qty_box_2" id="qty_box_2" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Total</label>
                                <input type="number" name="total_2" id="total_2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <input type="hidden" name="prepared_by" value="{{ auth()->id() }}">
                    <input type="hidden" name="issued_date" value="{{ date('Y-m-d') }}">

                    <button type="submit" class="btn btn-success w-100">Submit</button>
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
