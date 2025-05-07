<?php
namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Rak;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['customer', 'package', 'plant', 'area', 'rak'])->get();
        return view('Part.index', compact('parts'));
    }

    public function create()
    {
        return view('Part.create', [
            'customers' => Customer::all(),
            'plants' => Plant::all(),
            'areas' => Area::all(),
            'raks' => Rak::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inv_id' => 'required',
            'part_name' => 'required',
            'part_number' => 'required',
            'id_customer' => 'required|exists:customers,id',
            'id_plant' => 'required|exists:tbl_plant,id',
            'id_area' => 'required|exists:tbl_area,id',
            'id_rak' => 'required|exists:tbl_rak,id',
            'type_pkg' => 'required',
            'qty' => 'required|integer'
        ]);

        $part = Part::create($validated);
        Package::create([
            'type_pkg' => $validated['type_pkg'],
            'qty' => $validated['qty'],
            'id_part' => $part->id
        ]);

        return redirect()->route('Part.index')->with('success', 'Part created successfully.');
    }

    public function edit(Part $part)
    {
        return view('Part.edit', [
            'part' => $part->load('package'),
            'customers' => Customer::all(),
            'plants' => Plant::all(),
            'areas' => Area::all(),
            'raks' => Rak::all(),
        ]);
    }

    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'inv_id' => 'required',
            'part_name' => 'required',
            'part_number' => 'required',
            'id_customer' => 'required|exists:customers,id',
            'id_plant' => 'required|exists:tbl_plant,id',
            'id_area' => 'required|exists:tbl_area,id',
            'id_rak' => 'required|exists:tbl_rak,id',
            'type_pkg' => 'required',
            'qty' => 'required|integer'
        ]);

        $part->update($validated);
        $part->package()->update([
            'type_pkg' => $validated['type_pkg'],
            'qty' => $validated['qty'],
        ]);

        return redirect()->route('Part.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->package()->delete();
        $part->delete();

        return redirect()->route('parts.index')->with('success', 'Part deleted successfully.');
    }
}

