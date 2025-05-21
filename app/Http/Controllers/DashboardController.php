<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\PlanStock;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->query('month');
        $selectedCustomer = $request->query('customer');
        $selectedCategory = $request->query('category'); // Tambahan: ambil query filter kategori

        $categories = Category::all(); // untuk dropdown
        $customers = Customer::all();  // untuk dropdown

        // Ambil list bulan
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

        if ($selectedCategory) {
            $stoQuery->whereHas('part.category', function ($query) use ($selectedCategory) {
                $query->where('id', $selectedCategory);
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

        if ($selectedCategory) {
            $dailyStockQuery->whereHas('inventory.part.category', function ($query) use ($selectedCategory) {
                $query->where('id', $selectedCategory);
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
            'selectedCategory',
            'stoChartData',
            'categories'
        ));
    }


    public function getStoChartData(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $customer = $request->query('customer');

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $query = PlanStock::with(['inventory.part.customer'])
            ->whereBetween('created_at', [$start, $end]);

        if ($customer) {
            $query->whereHas('inventory.part.customer', function ($q) use ($customer) {
                $q->where('username', $customer);
            });
        }

        $logs = $query->get();

        $stoChartData = $logs->groupBy(function ($log) {
            return $log->inventory->part->customer->username ?? 'Unknown';
        })->map(function ($group) {
            return $group->sum('plan_stock_after');
        });

        return response()->json([
            'categories' => $stoChartData->keys()->values(),
            'data' => $stoChartData->values()
        ]);
    }


    // chart buat daily stock log
    public function getDailyChartData(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $customer = $request->query('customer');
        $weekFilter = $request->query('week'); // bisa kosong

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $partsQuery = Part::with(['forecast', 'inventories']);

        if ($customer) {
            $partsQuery->whereHas('customer', function ($q) use ($customer) {
                $q->where('username', $customer);
            });
        }

        $parts = $partsQuery->get();

        $min = [];
        $actual = [];
        $max = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayOfMonth = $date->day;
            $currentWeek = ceil($dayOfMonth / 7);

            // Hanya filter jika weekFilter tidak kosong
            if (!empty($weekFilter) && $currentWeek != $weekFilter) {
                continue;
            }

            foreach ($parts as $part) {
                $invId = $part->Inv_id;
                $inventoryIds = $part->inventories->pluck('id');

                $actualVal = (int) DailyStockLog::whereIn('id_inventory', $inventoryIds)
                    ->whereDate('created_at', $date)
                    ->sum('Total_qty');

                if (!isset($actual[$invId])) {
                    $actual[$invId] = 0;
                }
                $actual[$invId] += $actualVal;

                if (!isset($min[$invId]) || !isset($max[$invId])) {
                    $forecast = $part->forecast()
                        ->whereMonth('forecast_month', $date->month)
                        ->whereYear('forecast_month', $date->year)
                        ->first();

                    $min[$invId] = (int) ($forecast->min ?? 0);
                    $max[$invId] = (int) ($forecast->max ?? 0);
                }
            }
        }

        $minFormatted = [];
        $actualFormatted = [];
        $maxFormatted = [];

        foreach ($actual as $invId => $actualVal) {
            $invKey = (string) $invId;
            $minFormatted[] = ['x' => $invKey, 'y' => $min[$invId]];
            $actualFormatted[] = ['x' => $invKey, 'y' => $actualVal];
            $maxFormatted[] = ['x' => $invKey, 'y' => $max[$invId]];
        }

        return response()->json([
            'series' => [
                ['name' => 'Min', 'data' => $minFormatted],
                ['name' => 'Actual', 'data' => $actualFormatted],
                ['name' => 'Max', 'data' => $maxFormatted],
            ]
        ]);
    }


    // chart buat stock daily
    public function getDailyStockPerDayData(Request $request)
    {
        $customer = $request->query('customer');
        $category = $request->query('category');
        $today = now()->toDateString();

        $partsQuery = Part::with(['forecast', 'inventories']);

        if ($customer) {
            $partsQuery->whereHas('customer', function ($q) use ($customer) {
                $q->where('username', $customer);
            });
        }

        if ($category) {
            $partsQuery->where('id_category', $category);
        }

        $parts = $partsQuery->get();
        $data = [];

        foreach ($parts as $part) {
            $invId = $part->Inv_id;
            $inventoryIds = $part->inventories->pluck('id');

            $sumStockToday = DailyStockLog::whereIn('id_inventory', $inventoryIds)
                ->whereDate('created_at', $today)
                ->sum('stock_per_day');

            $data[] = [
                'x' => $invId,
                'y' => round($sumStockToday ?? 0, 2)
            ];
        }

        return response()->json([
            'series' => [
                [
                    'name' => 'Stock Hari Ini',
                    'data' => $data
                ]
            ]
        ]);
    }


}
