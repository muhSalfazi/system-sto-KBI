<?php
namespace App\Http\Controllers;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DailyStockImport; // Pastikan import ini ditambahkan

class DailyStockLogController extends Controller
{
    /**
     * Menampilkan daftar daily stock logs.
     */
    public function index()
    {
        $dailyStockLogs = DailyStockLog::with(['inventory', 'user'])->get();
        return view('Daily_stok.index', compact('dailyStockLogs'));
    }

    /**
     * Mengimpor file CSV atau Excel dan memproses data.
     */
    public function import(Request $request)
    {
        // Validasi file CSV atau Excel
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        // Menangani file yang diunggah
        $file = $request->file('file');

        // Menggunakan Maatwebsite Excel untuk mengimpor file dengan header
        Excel::import(new DailyStockImport, $file);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('daily-stock.index')->with('success', 'Data CSV/Excel berhasil diimpor');
    }
}

