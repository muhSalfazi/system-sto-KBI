@extends('layouts.user')

@section('contents')
  <div class="container">
    <div class="card mt-4 shadow-lg">
      <div class="card-body p-4">
         <h5><strong>PT Kyoraku Blowmolding Indonesia</strong></h5>
        <p class="text-sm"><strong>PPIC Department / Warehouse Section<strong></p>
        <div class="text-center">
          <h5>Inventory Card</h5>
        </div>
        <form class="w-100" method="POST" action="{{ route('sto.store', $inventory->id) }}">
            <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
          @csrf

          <!-- Part Name -->
          <div class="mb-3 row">
            <label class="col-md-3 col-form-label">Part Name</label>
            <div class="col-md-9">
              <input type="text" name="part_name" class="form-control" readonly
                value="{{ old('part_name', $inventory->part->Part_name ?? '') }}">
            </div>
          </div>

          <!-- Part Number -->
          <div class="mb-3 row">
            <label class="col-md-3 col-form-label">Part Number</label>
            <div class="col-md-9">
              <input type="text" name="part_number" class="form-control" readonly
                value="{{ old('part_number', $inventory->part->Part_number ?? '') }}">
            </div>
          </div>

          <!-- Inventory Code -->
          <div class="mb-3 row">
            <label class="col-md-3 col-form-label"><strong>Inventory Code</strong></label>
            <div class="col-md-9">
              <input type="hidden" name="id_inventory" value="{{ old('id_inventory', $inventory->id) }}">
              <input type="text" name="inventory_id" class="form-control" readonly
                value="{{ old('inventory_id', $inventory->part->Inv_id) }}">
            </div>
          </div>

          <!-- Category -->
          <div class="mb-3 row">
            <label class="col-md-3 col-form-label">Category</label>
            <div class="col-md-9">
              <input type="text" name="category" class="form-control" readonly
                value="{{ old('category', $inventory->part->category->name ?? '') }}">
            </div>
          </div>

          <!-- Status -->
          <div class="mb-3 row">
            <label class="col-md-3 col-form-label">Status</label>
            <div class="col-md-9">
              <select name="status" class="form-select">
                <option disabled>--Pilih--</option>
                @foreach(['OK','NG','Virgin','Funsai'] as $s)
                  <option value="{{ $s }}" {{ old('status', $inventory->status_product) == $s ? 'selected':'' }}>
                    {{ $s }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Quantity Inputs -->
          <div class="mb-3 p-3 border rounded">
            <h6 class="mb-3 text-center"><strong>ITEM COMPLETE</strong></h6>
            <div class="row">
              <div class="col-md-3">
                <label>Qty/Box</label>
                <input type="number" name="qty_per_box" id="qty_per_box" class="form-control" required
                  value="{{ old('qty_per_box', $inventory->qty_package) }}">
              </div>
              <div class="col-md-3">
                <label>Qty Box</label>
                <input type="number" name="qty_box" id="qty_box" class="form-control" required
                  value="{{ old('qty_box', $inventory->qty_box) }}">
              </div>
              <div class="col-md-3">
                <label>Total</label>
                <input type="number" name="total" id="total" class="form-control" readonly
                  value="{{ old('total', $inventory->total) }}">
              </div>
              <div class="col-md-3">
                <label>Grand Total</label>
                <input type="number" name="grand_total" id="grand_total" class="form-control" readonly
                  value="{{ old('grand_total', $inventory->grand_total) }}">
              </div>
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary mt-3 col-md-12" onclick="toggleOptionalQuantityInputs()">
              SHOW UNCOMPLETE ITEM
            </button>

            <div id="optionalQuantityInputs" class="row mt-3" style="display: none;">
              <h6 class="col-12"><strong>ITEM UNCOMPLETE</strong></h6>
              <div class="col-md-3">
                <label>Qty/Box</label>
                <input type="number" name="qty_per_box_2" id="qty_per_box_2" class="form-control"
                  value="{{ old('qty_per_box_2') }}">
              </div>
              <div class="col-md-3">
                <label>Qty Box</label>
                <input type="number" name="qty_box_2" id="qty_box_2" class="form-control"
                  value="{{ old('qty_box_2') }}">
              </div>
              <div class="col-md-3">
                <label>Total</label>
                <input type="number" name="total_2" id="total_2" class="form-control" readonly
                  value="{{ old('total_2') }}">
              </div>
            </div>
          </div>

          <!-- Dates & Metadata -->
          <div class="row">
            <div class="col-md-3 mb-3">
              <label>Issued Date</label>
              <input type="date" name="issued_date" class="form-control" readonly
                value="{{ old('issued_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3 mb-3">
              <label>Prepared By</label>
              <input type="hidden" name="prepared_by" value="{{ auth()->id() }}">
              <input type="text" class="form-control" readonly
                value="{{ Auth::user()->username }}">
            </div>
              <div class="col-md-3 mb-3">
              <label>Lokasi STO</label>
              <input type="text" class="form-control" readonly
                value="{{$inventory->part->plant->name ?? ''}}">
            </div>
            <div class="col-md-3 mb-3">
              <label>Area</label>
              <input type="hidden" name="prepared_by" value="{{ auth()->id() }}">
              <input type="text" class="form-control" readonly
                value="{{$inventory->part->area->nama_area ?? ''}}">
            </div>
          </div>

          <button type="submit" class="btn btn-success w-100">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <script>
     document.addEventListener("DOMContentLoaded", function() {
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
    });

    function toggleOptionalQuantityInputs(){
      $('#optionalQuantityInputs').toggle();
    }
  </script>

@endsection
