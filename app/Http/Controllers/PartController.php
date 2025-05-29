<?php
namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Category;
use App\Imports\PartsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $categories = Category::all();

        // Query untuk mengambil data Part, dengan filter kategori jika ada
        $query = Part::with(['customer', 'package', 'plant', 'area', 'category']);

        // Jika ada kategori yang dipilih, filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('id_category', $request->category_id);
        }

        // Ambil data berdasarkan query
        $parts = $query->get();

        // Kembalikan view dengan data yang sudah dipaginasi
        return view('Part.index', compact('parts', 'categories'));
    }


    public function create()
    {
        return view('Part.create', [
            'customers' => Customer::all(),
            'plants' => Plant::all(),
            'areas' => Area::all(),
            'categories' => Category::all(),
        ]);
    }


    public function store(Request $request)
    {
        // Validasi awal untuk field umum (tanpa id_area karena akan dicari/dibuat manual)
        $validated = $request->validate([
            'Inv_id' => 'required',
            'Part_name' => 'required',
            'Part_number' => 'required',
            'id_customer' => 'required|exists:tbl_customer,id',
            'id_category' => 'required|exists:tbl_category,id',
            'id_plan' => 'required|exists:tbl_plan,id',
            'nama_area' => 'required|string',
            'type_pkg' => 'required',
            'qty' => 'required|integer'
        ]);
        $duplicate = Part::where('Inv_id', $validated['Inv_id'])
            ->where('id_customer', $validated['id_customer'])
            ->first();

        if ($duplicate) {
            return redirect()->back()
                ->withErrors(['Inv_id' => 'Part dengan INV ID dan Customer ini sudah ada.'])
                ->withInput();
        }


        // Cari atau buat Area
        $area = Area::firstOrCreate(
            ['id_plan' => $validated['id_plan'], 'nama_area' => $validated['nama_area']]
        );


        // Buat part baru
        $part = Part::create([
            'Inv_id' => $validated['Inv_id'],
            'Part_name' => $validated['Part_name'],
            'Part_number' => $validated['Part_number'],
            'id_customer' => $validated['id_customer'],
            'id_category' => $validated['id_category'],
            'id_plan' => $validated['id_plan'],
            'id_area' => $area->id,
        ]);

        // Buat package-nya
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
        ]);
    }

    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'id_customer' => 'sometimes|exists:tbl_customer,id',
            'id_plan' => 'sometimes|exists:tbl_plan,id',
            'id_area' => 'sometimes|exists:tbl_area,id',
            'type_pkg' => 'sometimes',
            'qty' => 'sometimes|integer'
        ]);

        // Update data utama Part
        $part->update([
            'id_customer' => $validated['id_customer'] ?? $part->id_customer,
            'id_plan' => $validated['id_plan'] ?? $part->id_plan,
            'id_area' => $validated['id_area'] ?? $part->id_area,
        ]);

        // Cek apakah relasi package sudah ada
        if ($part->package) {
            // ✅ Update existing package
            $part->package->update([
                'type_pkg' => $validated['type_pkg'] ?? $part->package->type_pkg,
                'qty' => $validated['qty'] ?? $part->package->qty,
            ]);
        } else {
            // ✅ Create new package if not exists
            $part->package()->create([
                'type_pkg' => $validated['type_pkg'] ?? '',
                'qty' => $validated['qty'] ?? 0,
            ]);
        }

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

