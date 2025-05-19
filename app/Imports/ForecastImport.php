<?php

namespace App\Imports;

use App\Models\Forecast;
use App\Models\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ForecastImport implements ToCollection, WithHeadingRow
{
    public array $logs = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $no = $index + 2;

            // Cek kelengkapan kolom
            if (!isset($row['inv_id'], $row['hari_kerja'], $row['forecast_month'], $row['po_pcs'])) {
                $this->logs[] = "Baris $no: Kolom tidak lengkap.";
                continue;
            }

            // Cari Part berdasarkan Inv_id
            $part = Part::where('Inv_id', trim($row['inv_id']))->first();
            if (!$part) {
                $this->logs[] = "Baris $no: Part dengan INV ID '{$row['inv_id']}' tidak ditemukan.";
                continue;
            }

            // Parsing nilai
            $hariKerja = (int) $row['hari_kerja'];
            $poPcs = (int) $row['po_pcs'];

            try {
                $forecastMonth = Carbon::parse($row['forecast_month'])->startOfMonth();
            } catch (\Exception $e) {
                $this->logs[] = "Baris $no: Format tanggal tidak valid.";
                continue;
            }
            // hitung min dan max
            $min = (int) ceil($poPcs / max($hariKerja, 1));
            $max = $min * 3;

            Forecast::updateOrCreate(
                ['id_part' => $part->id, 'forecast_month' => $forecastMonth],
                [
                    'hari_kerja' => $hariKerja,
                    'po_pcs' => $poPcs,
                    'min' => $min,
                    'max' => $max,
                ]
            );

            // $this->logs[] = "Baris $no: Forecast untuk '{$row['inv_id']}' bulan {$forecastMonth->format('Y-m')} berhasil disimpan.";
        }
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}
