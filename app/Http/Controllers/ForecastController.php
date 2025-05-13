<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forecast;
use App\Models\Inventory;
use App\Models\Part;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Imports\ForecastImport;
use Maatwebsite\Excel\Facades\Excel;

class ForecastController extends Controller
{
    public function index()
    {
        $forecasts = Forecast::with('inventory.part.customer')->get();

        return view('Forecast.index', compact('forecasts'));
    }

    public function create()
    {
        $parts = Part::all();
        return view('Forecast.create', compact('parts'));
    }

    public function edit($id)
    {
        $forecast = Forecast::with('inventory.part')->findOrFail($id);
        return view('Forecast.edit', compact('forecast'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'hari_kerja' => 'required|integer|min:1',
        ]);

        $forecast = Forecast::findOrFail($id);
        $totalOrder = $forecast->inventory->plan_stock;

        $min = (int) ceil($totalOrder / $request->hari_kerja);
        $max = $min * 3;

        $forecast->update([
            'hari_kerja' => $request->hari_kerja,
            'min' => $min,
            'max' => $max,
        ]);

        return redirect()->route('forecast.index')->with('success', 'Forecast berhasil diupdate.');
    }

    // Hapus forecast
    public function destroy($id)
    {
        $forecast = Forecast::findOrFail($id);
        $forecast->delete();

        return redirect()->route('forecast.index')->with('success', 'Forecast berhasil dihapus.');
    }

    // Menyimpan data forecast
    public function store(Request $request)
    {
        $request->validate([
            'id_part' => 'required|exists:tbl_part,id',
            'plan_stock' => 'required|integer|min:1|max:31', // Hari kerja
        ]);

        // Cari inventory berdasarkan id_part
        $inventory = Inventory::where('id_part', $request->id_part)->latest()->first();

        if (!$inventory) {
            return redirect()->back()->withErrors(['id_part' => 'Inventory tidak ditemukan untuk Part ini.']);
        }

        $totalOrder = $inventory->plan_stock;
        $hariKerja = (int) $request->plan_stock;

        $min = (int) ceil($totalOrder / $hariKerja);
        $max = $min * 3;

        Forecast::updateOrCreate(
            ['id_inventory' => $inventory->id],
            [
                'hari_kerja' => $hariKerja,
                'min' => $min,
                'max' => $max,
            ]
        );

        return redirect()->route('forecast.index')->with('success', 'Data forecast berhasil disimpan.');
    }
    // Mengimpor data forecast dari file Excel

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $import = new ForecastImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('forecast.index')
            ->with('success', 'Import Forecast selesai.')
            ->with('import_logs', $import->getLogs());
    }
}
