<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Part;
use App\Models\Category;
use App\Models\PlanStock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            if (!isset($row['inv_id'], $row['part_name'], $row['part_number'], $row['kategori'], $row['plan_stock'], $row['status'])) {
                $this->logs[] = "Baris $rowNumber: Kolom tidak lengkap.";
                continue;
            }

            $part = Part::where('Inv_id', $row['inv_id'])->first();
            if (!$part) {
                $this->logs[] = "Baris $rowNumber: Part tidak ditemukan (INV ID: {$row['inv_id']}).";
                continue;
            }

            $category = Category::where('name', $row['kategori'])->first();
            if (!$category) {
                $this->logs[] = "Baris $rowNumber: Kategori tidak ditemukan ({$row['kategori']}).";
                continue;
            }

            $existingSto = Inventory::where('id_part', $part->id)
                ->latest()
                ->first();

            $planStock = (int) $row['total_qty'];
            $actStock = $existingSto ? (int) $existingSto->act_stock : 0;


            $gap = $planStock - $actStock;
            $remark = ($gap !== 0) ? 'abnormal' : 'normal';
            $note_remark = ($gap === 0) ? null : 'gap: ' . $gap;

            $addRemark = trim($row['status']);
            if ($addRemark && strtolower($addRemark) !== $remark) {
                $remark .= ' (' . $addRemark . ')';
            }

            if ($existingSto) {
                // Cek log terakhir
                $lastPlanLog = PlanStock::where('id_inventory', $existingSto->id)
                    ->latest('created_at')
                    ->first();

                $lastUpdateDate = $lastPlanLog ? Carbon::parse($lastPlanLog->created_at) : null;

                // Kalau log terakhir masih bulan ini → SKIP dan tampilkan alert
                if ($lastUpdateDate && $lastUpdateDate->isSameMonth(Carbon::now())) {
                    $this->logs[] = "Baris $rowNumber: ❗ Plan stock untuk INV ID {$row['inv_id']} sudah pernah diupdate bulan ini.";
                    continue;
                }

                // Lewat sebulan → update + simpan log
                $before = $existingSto->plan_stock;

                $existingSto->update([
                    'plan_stock' => $planStock,
                    // 'act_stock' => $actStock,
                    'remark' => $remark,
                    'note_remark' => $note_remark,
                ]);

                PlanStock::create([
                    'id_inventory' => $existingSto->id,
                    'plan_stock_before' => $before,
                    'plan_stock_after' => $planStock,
                ]);

                $this->logs[] = "Baris $rowNumber: Plan stock di-*update* (INV ID: {$row['inv_id']}, Remark: $remark).";
                continue;
            }

            // Jika inventory belum ada → buat baru + log awal
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

            // $this->logs[] = "Baris $rowNumber: ➕ Data baru disimpan (INV ID: {$row['inv_id']}, Remark: $remark).";
        }
    }
}
