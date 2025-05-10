<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use App\Models\Area;
use App\Models\Plant;
use Illuminate\Http\Request;

class DetailLokasiController extends Controller
{
    public function index()
    {
        // $raks = Rak::with('area.plant')->get();
        $raks = Rak::with('area.plant')->get();
        return view('Detail_Lokasi.index', compact('raks'));
    }

    public function create()
    {
        // return view('Detail_Lokasi.create', compact('areas'));
        return view('Detail_Lokasi.create', [
            'plans' => Plant::all(),
            'areas' => Area::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rak' => 'required',
            'id_plan' => 'required|exists:tbl_plan,id',
            'id_area' => 'nullable|exists:tbl_area,id',
            'nama_area_baru' => 'nullable|string'
        ]);

        // Gunakan area lama jika dipilih, atau buat baru jika diisi
        if ($validated['id_area']) {
            $areaId = $validated['id_area'];
        } elseif (!empty($validated['nama_area_baru'])) {
            $newArea = Area::create([
                'nama_area' => $validated['nama_area_baru'],
                'id_plan' => $validated['id_plan'],
            ]);
            $areaId = $newArea->id;
        } else {
            return back()->withErrors(['id_area' => 'Pilih area atau masukkan area baru']);
        }

        Rak::create([
            'nama_rak' => $validated['nama_rak'],
            'id_area' => $areaId
        ]);

        return redirect()->route('detail-lokasi.index')->with('success', 'Detail lokasi berhasil ditambahkan.');
    }

    public function edit(Rak $rak)
    {
        $plans = Plant::all();
        $areas = Area::all();
        return view('Detail_Lokasi.edit', compact('rak', 'plans', 'areas'));
    }

    public function update(Request $request, Rak $rak)
    {
        $request->validate([
            'nama_rak' => 'sometimes|string|max:255',
            'id_area' => 'sometimes|exists:tbl_area,id',
        ]);

        $rak->update([
            'nama_rak' => $request->nama_rak,
            'id_area' => $request->id_area,
        ]);

        return redirect()->route('detail-lokasi.index')->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Rak $rak)
    {
        $rak->delete();
        return redirect()->route('detail-lokasi.index')->with('success', 'Rak berhasil dihapus.');
    }
}
