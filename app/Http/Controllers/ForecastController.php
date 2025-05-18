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
    public function index(Request $request)
    {
        $query = Forecast::with(['part.customer']);

        // Filter berdasarkan customer (username)
        if ($request->filled('customer')) {
            $query->whereHas('part.customer', function ($q) use ($request) {
                $q->where('username', $request->customer);
            });
        }

        // Filter berdasarkan forecast_month
        if ($request->filled('forecast_month')) {
            $month = Carbon::createFromFormat('Y-m', $request->forecast_month)->startOfMonth()->format('Y-m-d');
            $query->where('forecast_month', $month);
        }

        $forecasts = $query->get();

        // Ambil list customer untuk select option
        $customers = Part::with('customer')->get()->pluck('customer')->unique('id')->filter()->values();

        return view('Forecast.index', compact('forecasts', 'customers'));
    }

    public function create()
    {
        $parts = Part::all();
        return view('Forecast.create', compact('parts'));
    }
    public function edit($id)
    {
        $forecast = Forecast::with('part')->findOrFail($id);
        return view('Forecast.edit', compact('forecast'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'forecast_month' => 'required|date_format:Y-m',
            'qty_box' => 'required|integer|min:1',
            'po_pcs' => 'required|integer|min:1',
            'hari_kerja' => 'required|integer|min:1',
        ]);

        $forecast = Forecast::findOrFail($id);

        $poPcs = (int) $request->po_pcs;
        $hariKerja = (int) $request->hari_kerja;

        $min = (int) ceil($poPcs / max($hariKerja, 1));
        $max = $min * 3;

        $forecast->update([
            'forecast_month' => Carbon::createFromFormat('Y-m', $request->forecast_month)->startOfMonth(),
            'qty_box' => (int) $request->qty_box,
            'PO_pcs' => $poPcs,
            'hari_kerja' => $hariKerja,
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
            'inv_id' => 'required|exists:tbl_part,Inv_id',
            'forecast_month' => 'required|date_format:Y-m',
            'qty_box' => 'required|integer|min:1',
            'po_pcs' => 'required|integer|min:1',
            'hari_kerja' => 'required|integer|min:1',
        ]);

        $part = Part::where('Inv_id', $request->inv_id)->first();

        if (!$part) {
            return redirect()->back()->withErrors(['inv_id' => 'Part tidak ditemukan.']);
        }

        // Konversi forecast_month ke awal bulan
        $forecastMonth = Carbon::createFromFormat('Y-m', $request->forecast_month)->startOfMonth();

        $poPcs = (int) $request->po_pcs;
        $hariKerja = (int) $request->hari_kerja;
        $qtyBox = (int) $request->qty_box;

        $min = (int) ceil($poPcs / max($hariKerja, 1));
        $max = $min * 3;

        Forecast::updateOrCreate(
            ['id_part' => $part->id, 'forecast_month' => $forecastMonth],
            [
                'hari_kerja' => $hariKerja,
                'qty_box' => $qtyBox,
                'PO_pcs' => $poPcs,
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
