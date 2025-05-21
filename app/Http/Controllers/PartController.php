<?php
namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Rak;
use App\Models\Category;
use App\Imports\PartsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartController extends Controller
{public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $categories = Category::all();

        // Query untuk mengambil data Part, dengan filter kategori jika ada
        $query = Part::with(['customer', 'package', 'plant', 'area', 'rak', 'category']);

        // Jika ada kategori yang dipilih, filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('id_category', $request->category_id);
        }

        // Ambil data berdasarkan query
        $parts = $query->get();

        // Kembalikan view dengan data yang sudah dipaginasi
        return view('Part.index', compact('parts','categories'));
    }


    public function create()
    {
        return view('Part.create', [
            'customers' => Customer::all(),
            'plants' => Plant::all(),
            'areas' => Area::all(),
            'raks' => Rak::all(),
            'categories' => Category::all(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'Inv_id' => 'required|unique:tbl_part,Inv_id|',
            'Part_name' => 'required',
            'Part_number' => 'required',
            'id_customer' => 'required|exists:tbl_customer,id',
            'id_category' => 'required|exists:tbl_category,id',
            'id_plan' => 'required|exists:tbl_plan,id',
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

        return redirect()->route('parts.index')->with('success', 'Part created successfully.');
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
            'id_customer' => 'sometimes|exists:tbl_customer,id',
            'id_plan' => 'sometimes|exists:tbl_plan,id',
            'id_area' => 'sometimes|exists:tbl_area,id',
            'id_rak' => 'sometimes|exists:tbl_rak,id',
            'type_pkg' => 'sometimes',
            'qty' => 'sometimes|integer'
        ]);
        $part->update([
            'id_customer' => $validated['id_customer'],
            'id_plan' => $validated['id_plan'],
            'id_area' => $validated['id_area'],
            'id_rak' => $validated['id_rak'],
        ]);

        $part->package()->update([
            'type_pkg' => $validated['type_pkg'],
            'qty' => $validated['qty'],
        ]);

        return redirect()->route('parts.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->package()->delete();
        $part->delete();

        return redirect()->route('parts.index')->with('success', 'Part deleted successfully.');
    }


    // select part area
    public function getAreas($plantId)
    {
        $areas = Area::where('id_plan', $plantId)->get();
        return response()->json($areas);
    }

    public function getRaks($areaId)
    {
        $raks = Rak::where('id_area', $areaId)->get();
        return response()->json($raks);
    }

    public function import(Request $request)
    {
        $request->validate([
             'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $importer = new PartsImport();
            Excel::import($importer, $request->file('file'));

            // Menyimpan log hasil ke session
            session()->flash('import_logs', $importer->getLogs());

            return redirect()->route('parts.index')->with('success', 'Import Berhasil.');
        } catch (\Exception $e) {
            Log::error('Import gagal: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('parts.index')->with('error', 'Terjadi kesalahan saat melakukan import. Silakan coba lagi.');
        }
    }


}

