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
        $forecasts = Forecast::with('part.customer')->get();

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


    public function store(Request $request)
    {
        $request->validate([
            'id_part' => 'required|exists:tbl_part,Inv_id',
            'jumlah_po' => 'required|numeric|min:1',
            'plan_stock' => 'required|integer|min:1',
        ]);

        // Cari inventory berdasarkan id_part
        $part = Part::where('Inv_id', $request->id_part)->latest()->first();

        if (!$part) {
            return redirect()->back()->withErrors(['id_part' => 'Inventory tidak ditemukan untuk Part ini.']);
        }

        $jumlahPO = (int) $request->jumlah_po;
        $hariKerja = (int) $request->plan_stock;

        $min = (int) ceil($jumlahPO / $hariKerja);
        $max = $min * 3;

        Forecast::updateOrCreate(
            ['id_part' => $part->id],
            [
                'hari_kerja' => $hariKerja,
                'Qty_Box' => $jumlahPO,
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
