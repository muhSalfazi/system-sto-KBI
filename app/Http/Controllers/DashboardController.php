<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->query('month');
        $selectedCustomer = $request->query('customer');

        // Semua customer
        $customers = Customer::all();

        // List bulan dari inventory
        $months = Inventory::selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as month")
            ->distinct()
            ->orderBy('month')
            ->pluck('month');

        // Ambil data STO
        $stoQuery = Inventory::with('part.customer');

        if ($selectedMonth) {
            $stoQuery->whereMonth('updated_at', Carbon::parse($selectedMonth)->month)
                ->whereYear('updated_at', Carbon::parse($selectedMonth)->year);
        }

        if ($selectedCustomer) {
            $stoQuery->whereHas('part.customer', function ($query) use ($selectedCustomer) {
                $query->where('username', $selectedCustomer);
            });
        }

        $stoData = $stoQuery->get();

        // Hitung total plan_stock per customer
        $stoChartData = $stoData->groupBy(function ($item) {
            return $item->part->customer->username ?? 'Unknown';
        })->map(function ($group) {
            return $group->sum('plan_stock');
        });

        // Daily stock log
        $dailyStockQuery = DailyStockLog::with('inventory.part.customer');

        if ($selectedMonth) {
            $dailyStockQuery->whereMonth('updated_at', Carbon::parse($selectedMonth)->month)
                ->whereYear('updated_at', Carbon::parse($selectedMonth)->year);
        }

        if ($selectedCustomer) {
            $dailyStockQuery->whereHas('inventory.part.customer', function ($query) use ($selectedCustomer) {
                $query->where('username', $selectedCustomer);
            });
        }

        $dailyStockData = $dailyStockQuery->get();

        return view('Dashboard.index', compact(
            'months',
            'customers',
            'stoData',
            'dailyStockData',
            'selectedMonth',
            'selectedCustomer',
            'stoChartData'
        ));
    }
    public function getDailyChartData(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $customer = $request->query('customer');

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $query = DailyStockLog::with('inventory.part.customer')
            ->whereBetween('updated_at', [$start, $end]);

        if ($customer) {
            $query->whereHas('inventory.part.customer', function ($q) use ($customer) {
                $q->where('username', $customer);
            });
        }

        $logs = $query->get();

        // Ambil semua part name unik
        $partNames = $logs->pluck('inventory.part.Inv_id')->filter()->unique()->values();

        // Ambil list tanggal dalam bulan
        $days = collect();
        for ($i = 0; $i <= $end->diffInDays($start); $i++) {
            $days->push($start->copy()->addDays($i));
        }

        // Buat data series
        $series = [];
        foreach ($days as $day) {
            $dataPerPart = [];

            foreach ($partNames as $partName) {
                $qty = $logs->filter(function ($item) use ($day, $partName) {
                    return Carbon::parse($item->updated_at)->isSameDay($day)
                        && ($item->inventory->part->Inv_id ?? 'Unknown') === $partName;
                })->sum('Total_qty');

                // Format sesuai kebutuhan tooltip custom
                $dataPerPart[] = [
                    'x' => $partName,
                    'y' => $qty,
                    'tanggal' => $day->format('d M')
                ];
            }

            $series[] = [
                'name' => $day->format('d M'),
                'data' => $dataPerPart
            ];
        }

        return response()->json([
            'categories' => $partNames,
            'series' => $series
        ]);
    }

    public function getStoChartData(Request $request)
{
    $month = $request->query('month', now()->format('Y-m'));
    $customer = $request->query('customer');

    $start = Carbon::parse($month)->startOfMonth();
    $end = Carbon::parse($month)->endOfMonth();

    $query = Inventory::with('part.customer')
        ->whereBetween('updated_at', [$start, $end]);

    if ($customer) {
        $query->whereHas('part.customer', function ($q) use ($customer) {
            $q->where('username', $customer);
        });
    }

    $stoData = $query->get();

    $stoChartData = $stoData->groupBy(function ($item) {
        return $item->part->customer->username ?? 'Unknown';
    })->map(function ($group) {
        return $group->sum('plan_stock');
    });

    return response()->json([
        'categories' => $stoChartData->keys()->values(),
        'data' => $stoChartData->values()
    ]);
}




}
