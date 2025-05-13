<?php
namespace App\Imports;

use App\Models\Forecast;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ForecastImport implements ToCollection, WithHeadingRow
{
    public array $logs = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $no = $index + 2;

            if (!isset($row['inv_id'], $row['hari_kerja'])) {
                $this->logs[] = "Baris $no: Kolom tidak lengkap.";
                continue;
            }

            $part = Part::where('Inv_id', $row['inv_id'])->first();

            if (!$part) {
                $this->logs[] = "Baris $no: Part dengan INV ID '{$row['inv_id']}' tidak ditemukan.";
                continue;
            }

            $inventory = Inventory::where('id_part', $part->id)->latest()->first();

            if (!$inventory) {
                $this->logs[] = "Baris $no: Inventory tidak ditemukan untuk part '{$row['inv_id']}'.";
                continue;
            }

            $hariKerja = (int) $row['hari_kerja'];
            $planStock = $inventory->plan_stock ?? 0;

            $min = (int) ceil($planStock / max($hariKerja, 1));
            $max = $min * 3;

            Forecast::updateOrCreate(
                ['id_inventory' => $inventory->id],
                [
                    'hari_kerja' => $hariKerja,
                    'min' => $min,
                    'max' => $max
                ]
            );

            // $this->logs[] = "Baris $no: Forecast berhasil disimpan untuk INV ID '{$row['inv_id']}' (Min: $min, Max: $max)";
        }
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}

