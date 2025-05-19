<?php
namespace App\Http\Controllers;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyStockExport;
class DailyStockLogController extends Controller
{
    /**
     * Menampilkan daftar daily stock logs.
     */
    public function index(Request $request)
    {
        $query = DailyStockLog::with([
            'inventory.part.customer',
            'inventory.part.forecast',
            'user'
        ]);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $dailyStockLogs = $query->get();

        $statuses = ['OK', 'NG', 'VIRGIN', 'FUNSAI']; // untuk dropdown filter

        return view('Daily_stok.index', compact('dailyStockLogs', 'statuses'));
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

