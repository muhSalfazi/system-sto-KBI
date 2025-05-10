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
        $query = Inventory::with('part.plant', 'part.area', 'part.rak');

        // Jika ada kategori yang dipilih, filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('id_category', $request->category_id);
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
            'id_category' => 'required|exists:tbl_category,id',
            'plan_stock' => 'required|string',
            'status' => 'required|in:OK,NG,FUNSAI,VIRGIN',
        ]);

        Inventory::create([
            'id_part' => $request->id_part,
            'id_category' => $request->id_category,
            'plan_stock' => $request->plan_stock,
            'status' => $request->status,
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
            'id_category' => 'sometimes|exists:tbl_category,id',
            'status' => 'sometimes|in:OK,NG',
        ]);

        $sto = Inventory::findOrFail($id);

        $sto->update([
            'id_category' => $request->id_category,
            'status' => $request->status,
        ]);

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

        // Jalankan import dan tampung log hasil
        $importer = new StoImport();
        Excel::import($importer, $request->file('file'));

        // Simpan log ke session agar bisa ditampilkan di Blade
        Session::flash('import_logs', $importer->getLogs());

        return redirect()->route('sto.index')->with('success', 'Import Berhasil.');
    }


    // export
    // Fungsi untuk export ke Excel
    public function export(Request $request)
    {
        return Excel::download(new StoExport($request->category_id), 'List_STO_Data.xlsx');
    }
}
