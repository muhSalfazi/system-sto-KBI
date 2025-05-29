<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Part;
use App\Models\Customer;
use App\Models\PlanStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class StoImport implements ToCollection, WithHeadingRow
{
    protected array $logs = [];

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        $customers = Customer::all()->keyBy('username');
        $parts = Part::all()->keyBy(fn($p) => $p->Inv_id . '|' . $p->id_customer);

        foreach ($rows as $rowNumber => $row) {
            $rowNumber++;

            if (!isset($row['inv_id'], $row['part_name'], $row['part_number'], $row['customer'], $row['plan_stock'])) {
                $this->logs[] = "Baris $rowNumber: Kolom tidak lengkap.";
                continue;
            }

            $customer = $customers[trim($row['customer'])] ?? null;
            if (!$customer) {
                $this->logs[] = "Baris $rowNumber: Customer '{$row['customer']}' tidak ditemukan.";
                continue;
            }

            $key = trim($row['inv_id']) . '|' . $customer->id;
            $part = $parts[$key] ?? null;
            if (!$part) {
                $this->logs[] = "Baris $rowNumber: Part tidak ditemukan untuk '{$row['inv_id']}' dan '{$row['customer']}'.";
                continue;
            }

            $existingSto = Inventory::where('id_part', $part->id)->latest()->first();
            $planStock = (int) $row['plan_stock'];
            $actStock = $existingSto?->act_stock ?? 0;

            $gap = $planStock - $actStock;
            $remark = ($gap !== 0) ? 'abnormal' : 'normal';
            $note_remark = $gap !== 0 ? "gap: $gap" : null;

            if ($existingSto) {
                $lastUpdate = PlanStock::where('id_inventory', $existingSto->id)->latest('created_at')->first();
                if ($lastUpdate && Carbon::parse($lastUpdate->created_at)->isSameMonth(now())) {
                    $this->logs[] = "Baris $rowNumber: Plan stock sudah diupdate bulan ini.";
                    continue;
                }

                $existingSto->update([
                    'plan_stock' => $planStock,
                    'remark' => $remark,
                    'note_remark' => $note_remark,
                ]);

                PlanStock::create([
                    'id_inventory' => $existingSto->id,
                    'plan_stock_before' => $existingSto->plan_stock,
                    'plan_stock_after' => $planStock,
                ]);

                $this->logs[] = "Baris $rowNumber: Plan stock diupdate.";
                continue;
            }

            $newSto = Inventory::create([
                'id_part' => $part->id,
                'plan_stock' => $planStock,
                'act_stock' => $actStock,
                'remark' => $remark,
                'note_remark' => $note_remark,
            ]);

            PlanStock::create([
                'id_inventory' => $newSto->id,
                'plan_stock_before' => null,
                'plan_stock_after' => $planStock,
            ]);
        }
    }

}
