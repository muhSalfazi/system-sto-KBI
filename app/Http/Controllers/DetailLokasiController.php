<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use App\Models\Area;
use App\Models\Plant;
use Illuminate\Http\Request;
use App\Imports\DetailLokasiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'nama_rak' => 'required',
            'id_plan' => 'required|exists:tbl_plan,id',
            'id_area' => 'nullable|exists:tbl_area,id',
            'nama_area_baru' => 'nullable|string'
        ]);

        // Validasi khusus: tidak boleh isi dua-duanya
        $validator->after(function ($validator) use ($request) {
            if ($request->filled('id_area') && $request->filled('nama_area_baru')) {
                $validator->errors()->add('id_area', 'Tidak boleh mengisi area lama dan area baru secara bersamaan.');
            }

            if (!$request->filled('id_area') && !$request->filled('nama_area_baru')) {
                $validator->errors()->add('id_area', 'Wajib pilih salah satu: area lama atau input area baru.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Logika area
        if ($request->filled('id_area')) {
            $areaId = $request->id_area;
        } else {
            $newArea = Area::create([
                'nama_area' => $request->nama_area_baru,
                'id_plan' => $request->id_plan,
            ]);
            $areaId = $newArea->id;
        }

        Rak::create([
            'nama_rak' => $request->nama_rak,
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

    // Import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $importer = new DetailLokasiImport();
            Excel::import($importer, $request->file('file'));

            session()->flash('import_logs', $importer->getLogs());

            return back()->with('success', 'Import detail lokasi berhasil.');
        } catch (\Exception $e) {
            \Log::error('Gagal import detail lokasi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat import.');
        }
    }

    // get area by plan
    public function getByPlan($id)
    {
        $areas = Area::where('id_plan', $id)->get();
        return response()->json($areas);
    }


}
