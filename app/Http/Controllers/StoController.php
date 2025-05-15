<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Part;
use App\Models\Category;
use App\Imports\StoImport;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StoExport;

class StoController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $categories = Category::all();

        // Query untuk mengambil data STO, dengan filter kategori jika ada
        $query = Inventory::with('part.plant', 'part.area', 'part.rak', 'part.category');


        // Jika ada kategori yang dipilih, filter berdasarkan kategori dari relasi part
        if ($request->has('category_id') && $request->category_id != '') {
            $query->whereHas('part', function ($q) use ($request) {
                $q->where('id_category', $request->category_id);
            });
        }
        // Filter berdasarkan remark langsung dari tabel inventory
        if ($request->filled('remark')) {
            $query->where('remark', $request->remark);
        }

        // Ambil data berdasarkan query
        $parts = $query->get();

        // Tampilkan view dengan data parts dan categories untuk filter
        return view('STO.index', compact('parts', 'categories'));
    }

    public function create()
    {
        $parts = Part::all();
        $categories = Category::all();
        return view('STO.create', compact('parts', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_part' => 'required|exists:tbl_part,id',
            'plan_stock' => 'required|string',
        ]);

        Inventory::create([
            'id_part' => $request->id_part,
            'plan_stock' => $request->plan_stock,
        ]);

        return redirect()->route('sto.index')->with('success', 'Data STO berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sto = Inventory::with('part', 'category')->findOrFail($id); // Ambil data dengan relasi
        $parts = Part::all();
        $categories = Category::all();

        return view('STO.edit', compact('sto', 'parts', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'sometimes|in:OK,NG',
        ]);

        $sto = Inventory::findOrFail($id);

        return redirect()->route('sto.index')->with('success', 'Data STO berhasil diperbarui.');
    }

    public function destroy(Inventory $id)
    {
        // Hapus hanya data dari tabel Inventory (STO)
        $id->delete();

        return redirect()->route('sto.index')->with('success', 'Data STO berhasil dihapus.');
    }

    // import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $importer = new StoImport();
            Excel::import($importer, $request->file('file'));

            $logs = $importer->getLogs();

            if (count($logs) > 0) {
                Session::flash('import_logs', $logs);
            }

            return redirect()->route('sto.index')->with('success', 'Import selesai. Periksa log untuk detailnya.');
        } catch (\Exception $e) {
            \Log::error('Import stok gagal', ['error' => $e->getMessage()]);

            return redirect()->route('sto.index')->with([
                'error' => 'Terjadi kesalahan saat import: ' . $e->getMessage(),
            ]);
        }
    }

    // export
    // Fungsi untuk export ke Excel
    public function export(Request $request)
    {
        return Excel::download(new StoExport($request->category_id), 'List_STO_Data.xlsx');
    }
}
