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
use Illuminate\Support\Facades\Session;

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
        $parts = Part::with('customer')->get();
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
            'id' => 'required|exists:tbl_part,id',
            'forecast_month' => 'required|date_format:Y-m',
            'po_pcs' => 'required|integer|min:1',
            'hari_kerja' => 'required|integer|min:1',
        ]);

        $part = Part::where('id', $request->id)->first();

        if (!$part) {
            return redirect()->back()->withErrors(['id' => 'Part tidak ditemukan.']);
        }

        // Konversi forecast_month ke awal bulan
        $forecastMonth = Carbon::createFromFormat('Y-m', $request->forecast_month)->startOfMonth();

        $poPcs = (int) $request->po_pcs;
        $hariKerja = (int) $request->hari_kerja;

        $min = (int) ceil($poPcs / max($hariKerja, 1));
        $max = $min * 3;

        Forecast::updateOrCreate(
            ['id_part' => $part->id, 'forecast_month' => $forecastMonth],
            [
                'hari_kerja' => $hariKerja,
                'PO_pcs' => $poPcs,
                'min' => $min,
                'max' => $max,
            ]
        );

        return redirect()->route('forecast.index')->with('success', 'Data forecast berhasil disimpan.');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        try {
            $importer = new ForecastImport();
            Excel::import($importer, $request->file('file'));

            $logs = $importer->getLogs();

            if (count($logs) > 0) {
                Session::flash('import_logs', $logs);
            }

            return redirect()->route('forecast.index')->with('success', 'Import selesai.');
        } catch (\Exception $e) {
            \Log::error('Import forecast gagal', ['error' => $e->getMessage()]);

            return redirect()->route('forecast.index')->with([
                'error' => 'Terjadi kesalahan saat import: ' . $e->getMessage(),
            ]);
        }
    }

}
