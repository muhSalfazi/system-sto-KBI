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


       public function getDailyChartData(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $customer = $request->query('customer');

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
            $dayLabel = $date->format('d M');

            foreach ($parts as $part) {
                $label = $dayLabel . ' - ' . $part->Inv_id;

                $forecast = $part->forecast()
                    ->whereMonth('forecast_month', $date->month)
                    ->whereYear('forecast_month', $date->year)
                    ->first();

                $minVal = (int) ($forecast->min ?? 0);
                $maxVal = (int) ($forecast->max ?? 0);

                $inventoryIds = $part->inventories->pluck('id');

                $actualVal = (int) DailyStockLog::whereIn('id_inventory', $inventoryIds)
                    ->whereDate('created_at', $date)
                    ->sum('Total_qty');

                $min[] = ['x' => $label, 'y' => $minVal];
                $actual[] = ['x' => $label, 'y' => $actualVal];
                $max[] = ['x' => $label, 'y' => $maxVal];
            }
        }

        return response()->json([
            'series' => [
                ['name' => 'Min', 'data' => $min],
                ['name' => 'Actual', 'data' => $actual],
                ['name' => 'Max', 'data' => $max],
            ]
        ]);
    }


}
