<?php

namespace App\Imports;

use App\Models\Forecast;
use App\Models\Part;
use App\Models\Customer;
use Illuminate\Support\Collection;
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

            // Normalisasi kolom
            $inv_id = trim($row['inv_id'] ?? '');
            $customer_name = trim($row['customer'] ?? $row['Customer'] ?? '');
            $hari_kerja = $row['hari_kerja'] ?? null;
            $forecast_month_raw = $row['forecast_month'] ?? null;
            $po_pcs = $row['po_pcs'] ?? null;

            // Validasi kolom
            if (!$inv_id || !$customer_name || !$hari_kerja || !$forecast_month_raw || !$po_pcs) {
                $this->logs[] = "Baris $no: Kolom tidak lengkap.";
                continue;
            }

            // Cari customer
            $customer = Customer::where('username', $customer_name)->first();
            if (!$customer) {
                $this->logs[] = "Baris $no: Customer '{$customer_name}' tidak ditemukan.";
                continue;
            }

            // Cari part berdasarkan inv_id + id_customer
            $part = Part::where('Inv_id', $inv_id)
                        ->where('id_customer', $customer->id)
                        ->first();

            if (!$part) {
                $this->logs[] = "Baris $no: Part dengan INV ID '{$inv_id}' untuk customer '{$customer_name}' tidak ditemukan.";
                continue;
            }

            try {
                $forecastMonth = Carbon::parse($forecast_month_raw)->startOfMonth();
            } catch (\Exception $e) {
                $this->logs[] = "Baris $no: Format tanggal tidak valid.";
                continue;
            }

            $hariKerja = (int) $hari_kerja;
            $poPcs = (int) $po_pcs;
            $min = (int) ceil($poPcs / max($hariKerja, 1));
            $max = $min * 3;

            Forecast::updateOrCreate(
                ['id_part' => $part->id, 'forecast_month' => $forecastMonth],
                [
                    'hari_kerja' => $hariKerja,
                    'PO_pcs' => $poPcs,
                    'min' => $min,
                    'max' => $max,
                ]
            );

            // $this->logs[] = "Baris $no: Forecast berhasil disimpan untuk INV ID '{$inv_id}' customer '{$customer_name}'.";
        }
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}
