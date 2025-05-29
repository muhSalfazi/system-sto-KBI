<?php

namespace App\Imports;

use App\Models\Forecast;
use App\Models\Part;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ForecastImport implements ToCollection, WithHeadingRow
{
    public array $logs = [];

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        $customers = Customer::all()->keyBy('username');
        $parts = Part::all()->keyBy(fn($p) => $p->Inv_id . '|' . $p->id_customer);

        foreach ($rows as $index => $row) {
            $no = $index + 2;
            $inv_id = trim($row['inv_id'] ?? '');
            $customer_name = trim($row['customer'] ?? $row['Customer'] ?? '');

            if (!$inv_id || !$customer_name || empty($row['hari_kerja']) || empty($row['forecast_month']) || empty($row['po_pcs'])) {
                $this->logs[] = "Baris $no: Kolom tidak lengkap.";
                continue;
            }

            $customer = $customers[$customer_name] ?? null;
            if (!$customer) {
                $this->logs[] = "Baris $no: Customer '{$customer_name}' tidak ditemukan.";
                continue;
            }

            $key = $inv_id . '|' . $customer->id;
            $part = $parts[$key] ?? null;
            if (!$part) {
                $this->logs[] = "Baris $no: Part '{$inv_id}' tidak ditemukan.";
                continue;
            }

            try {
                $forecastMonth = is_numeric($row['forecast_month'])
                    ? Carbon::instance(Date::excelToDateTimeObject($row['forecast_month']))->startOfMonth()
                    : Carbon::parse($row['forecast_month'])->startOfMonth();
            } catch (\Exception $e) {
                $this->logs[] = "Baris $no: Format tanggal tidak valid.";
                continue;
            }

            $hariKerja = (int) $row['hari_kerja'];
            $poPcs = (int) $row['po_pcs'];
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
        }
    }

}
