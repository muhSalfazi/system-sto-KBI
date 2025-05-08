<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Part;
use App\Models\Category;

class StoController extends Controller
{
    public function index()
    {
        $parts = Inventory::with('part.plant', 'part.area', 'part.rak')->get();
        return view('STO.index', compact('parts'));
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
            'status' => 'required|in:OK,NG',
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
        'id_part' => 'required|exists:tbl_part,id',
        'id_category' => 'required|exists:tbl_category,id',
        'status' => 'required|in:OK,NG',
    ]);

    $sto = Inventory::findOrFail($id);

    $sto->update([
        'id_part' => $request->id_part,
        'id_category' => $request->id_category,
        'status' => $request->status,
    ]);

    return redirect()->route('sto.index')->with('success', 'Data STO berhasil diperbarui.');
}

public function destroy(Inventory $sto)
{
    $sto->package()->delete();
    $sto->delete();

    return redirect()->route('parts.index')->with('success', 'Part deleted successfully.');
}

}
