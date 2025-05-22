<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Part;
use App\Models\Category;
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
        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            // Validasi kolom
            if (!isset($row['inv_id'],$row['part_name'], $row['part_number'],$row['customer'],$row['kategori'], $row['plan_stock'], $row['status'])) {
                $this->logs[] = "Baris $rowNumber: Kolom tidak lengkap.";
                continue;
            }

            // Cari customer berdasarkan username
            $customer = Customer::where('username', trim($row['customer']))->first();
            if (!$customer) {
                $this->logs[] = "Baris $rowNumber: Customer '{$row['customer']}' tidak ditemukan.";
                continue;
            }

            // Cari Part berdasarkan inv_id dan id_customer
            $part = Part::where('Inv_id', trim($row['inv_id']))
                ->where('id_customer', $customer->id)
                ->first();

            if (!$part) {
                $this->logs[] = "Baris $rowNumber: Part tidak ditemukan untuk INV ID '{$row['inv_id']}' dan customer '{$row['customer']}'.";
                continue;
            }

            // Cek kategori (opsional kalau memang mau dipastikan sesuai)
            $category = Category::where('name', trim($row['kategori']))->first();
            if (!$category) {
                $this->logs[] = "Baris $rowNumber: Kategori '{$row['kategori']}' tidak ditemukan.";
                continue;
            }

            $existingSto = Inventory::where('id_part', $part->id)->latest()->first();

            $planStock = (int) $row['plan_stock'];
            $actStock = $existingSto ? (int) $existingSto->act_stock : 0;

            $gap = $planStock - $actStock;
            $remark = ($gap !== 0) ? 'abnormal' : 'normal';
            $note_remark = ($gap === 0) ? null : 'gap: ' . $gap;

            $addRemark = trim($row['status']);
            if ($addRemark && strtolower($addRemark) !== strtolower($remark)) {
                $remark .= ' (' . $addRemark . ')';
            }

            if ($existingSto) {
                $lastPlanLog = PlanStock::where('id_inventory', $existingSto->id)
                    ->latest('created_at')
                    ->first();

                $lastUpdateDate = $lastPlanLog ? Carbon::parse($lastPlanLog->created_at) : null;

                if ($lastUpdateDate && $lastUpdateDate->isSameMonth(Carbon::now())) {
                    $this->logs[] = "Baris $rowNumber: ❗ Plan stock untuk INV ID {$row['inv_id']} customer '{$row['customer']}' sudah pernah diupdate bulan ini.";
                    continue;
                }

                // Update STO
                $before = $existingSto->plan_stock;

                $existingSto->update([
                    'plan_stock' => $planStock,
                    'remark' => $remark,
                    'note_remark' => $note_remark,
                ]);

                PlanStock::create([
                    'id_inventory' => $existingSto->id,
                    'plan_stock_before' => $before,
                    'plan_stock_after' => $planStock,
                ]);

                $this->logs[] = "Baris $rowNumber: Plan stock diupdate untuk {$row['inv_id']} ({$row['customer']}) → Remark: $remark.";
                continue;
            }

            // STO belum ada → buat baru
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

            // $this->logs[] = "Baris $rowNumber: ➕ STO baru disimpan untuk INV ID {$row['inv_id']} ({$row['customer']})";
        }
    }
}
