<?php
namespace App\Http\Controllers;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyStockExport;
use Carbon\Carbon;


class DailyStockLogController extends Controller
{
    /**
     * Menampilkan daftar daily stock logs.
     */
    public function index(Request $request)
    {
        // Ambil log harian stok dengan relasi terkait
        $query = DailyStockLog::with([
            'inventory.part.customer',
            'user'
        ]);

        // Filter status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $dailyStockLogs = $query->get();

        // Looping tiap log untuk ambil forecast bulan yang sesuai
        foreach ($dailyStockLogs as $log) {
            $part = optional(optional($log->inventory)->part);

            if ($part && $log->created_at) {
                $forecastMonth = Carbon::parse($log->created_at)->startOfMonth();

                // Ambil forecast sesuai bulan created_at log
                $forecast = $part->forecast()
                    ->whereDate('forecast_month', $forecastMonth)
                    ->first();

                $log->forecast_min = $forecast->min ?? null;
                $log->forecast_max = $forecast->max ?? null;
            } else {
                $log->forecast_min = null;
                $log->forecast_max = null;
            }
        }

        $statuses = ['OK', 'NG', 'VIRGIN', 'FUNSAI'];

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

