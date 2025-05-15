<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Part;
use App\Models\Category;
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
                ->latest() // Ambil data terbaru (jika ada)
                ->first();

            $planStock = (int) $row['plan_stock'];
            $actStock = isset($row['act_stock']) ? (int) $row['act_stock'] : 0; // opsional jika ada act_stock di Excel

            // Hitung selisih dan remark
            $gap = $planStock - $actStock;
            $remark = ($gap !== 0) ? 'abnormal' : 'normal';

            // Tambahan remark dari kolom status
            $addRemark = trim($row['status']);
            if ($addRemark && strtolower($addRemark) !== $remark) {
                $remark .= ' (' . $addRemark . ')';
            }

            // Jika bulan sama, update saja
            if ($existingSto) {
                $createdAt = Carbon::parse($existingSto->created_at);
                if ($createdAt->isSameMonth(Carbon::now())) {
                    $existingSto->update([
                        'plan_stock' => $planStock,
                        'act_stock' => $actStock,
                        'remark'     => $remark,
                    ]);

                    $this->logs[] = "Baris $rowNumber: Plan stock di-*update* (INV ID: {$row['inv_id']}, Remark: $remark).";
                    continue;
                }
            }

            // Insert baru
            Inventory::create([
                'id_part'     => $part->id,
                'plan_stock'  => $planStock,
                'act_stock'   => $actStock,
                'remark'      => $remark,
            ]);

            $this->logs[] = "Baris $rowNumber: Data baru disimpan (INV ID: {$row['inv_id']}, Remark: $remark).";
        }
    }
}
