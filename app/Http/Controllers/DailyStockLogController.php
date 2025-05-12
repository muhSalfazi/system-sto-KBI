<?php
namespace App\Http\Controllers;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DailyStockImport;
use Illuminate\Support\Facades\DB;
use App\Exports\DailyStockExport;
class DailyStockLogController extends Controller
{
    /**
     * Menampilkan daftar daily stock logs.
     */
    public function index(Request $request)
    {
        $query = DailyStockLog::with(['inventory.part.customer', 'user']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $dailyStockLogs = $query->get();

        $statuses = ['OK', 'NG', 'VIRGIN', 'FUNSAI']; // untuk dropdown filter

        return view('Daily_stok.index', compact('dailyStockLogs', 'statuses'));
    }


    /**
     * Mengimpor file CSV atau Excel dan memproses data.
     */
    public function import(Request $request)
    {
        // Validasi file CSV atau Excel
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $importer = new DailyStockImport();
            Excel::import($importer, $request->file('file'));

            session()->flash('import_logs', $importer->getLogs());

            return redirect()->route('daily-stock.index')->with('success', 'Import stok harian berhasil.');
        } catch (\Exception $e) {
            \Log::error('Import stok gagal', ['error' => $e->getMessage()]);
            return redirect()->route('reports.edit')->with('error', 'Terjadi kesalahan saat mengimpor file.');
        }
    }

    public function edit($id)
    {
        $report = DailyStockLog::with(['inventory.part', 'user'])->findOrFail($id);
        $inventory = $report->inventory;

        return view('Daily_stok.edit', compact('report', 'inventory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:OK,NG,VIRGIN,FUNSAI',
            'qty_per_box' => 'required|numeric',
            'qty_box' => 'required|numeric',
            'qty_per_box_2' => 'nullable|numeric',
            'qty_box_2' => 'nullable|numeric',
            'grand_total' => 'required|numeric',
        ]);

        try {
            $report = DailyStockLog::findOrFail($id);
            $inventory = $report->inventory;

            // Update ke inventory (status dan lokasi)
            $inventory->status = $request->status;
            $inventory->save();

            // Hanya update Total_qty (grand_total) di log
            $report->update([
                'Total_qty' => $request->grand_total,
                'prepared_by' => auth()->id(),
            ]);

            return redirect()->route('daily-stock.index')->with('success', 'Laporan stok berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Gagal update stok: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update stok.');
        }
    }
    public function destroy($id)
    {
        try {
            $report = DailyStockLog::with('inventory')->findOrFail($id);

            // Kurangi act_stock di inventory
            $inventory = $report->inventory;
            if ($inventory) {
                $inventory->act_stock -= $report->Total_qty;
                $inventory->save();
            }

            // Hapus log daily stock
            $report->delete();

            return redirect()->route('daily-stock.index')->with('success', 'Laporan stok berhasil dihapus dan Act stok dikurangi.');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus laporan stok harian: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // Export daily stock logs to Excel
    public function export(Request $request)
    {
        $status = $request->status;
        return Excel::download(new DailyStockExport($status), 'daily_stock_logs.xlsx');
    }
}

