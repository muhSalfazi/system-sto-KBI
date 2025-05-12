<?php

namespace App\Imports;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DailyStockImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $logs = [];

    public function getLogs()
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        $rowNum = 2; // dimulai dari 2 karena baris 1 = header

        foreach ($rows as $row) {
            $invId = $row['inv_id'];
            $totalQty = (int) $row['total_qty'];
            $status = strtoupper(trim($row['status'] ?? 'OK'));

            $validStatus = ['OK', 'NG', 'VIRGIN', 'FUNSAI'];
            if (!in_array($status, $validStatus)) {
                $this->logs[] = "Baris {$rowNum}: Status tidak valid `{$status}`.";
                $rowNum++;
                continue;
            }

            $part = Part::where('Inv_id', $invId)->first();
            if (!$part) {
                $this->logs[] = "Baris {$rowNum}: Part tidak ditemukan untuk Inv ID: {$invId}";
                $rowNum++;
                continue;
            }

            $inventory = Inventory::where('id_part', $part->id)->first();
            if (!$inventory) {
                $this->logs[] = "Baris {$rowNum}: Inventory tidak ditemukan untuk Part ID: {$part->id}";
                $rowNum++;
                continue;
            }

            // Update inventory
            $inventory->act_stock += $totalQty;
            $inventory->status = $status;
            $inventory->updated_at = now();
            $inventory->save();

            // Simpan log
            // $this->logs[] = "Baris {$rowNum}: Stock berhasil ditambahkan (Qty: {$totalQty}, Status: {$status}) untuk INV ID: {$invId}.";

            // Tambahkan log DailyStockLog
            DailyStockLog::create([
                'id_inventory' => $inventory->id,
                'prepared_by' => auth()->id(),
                'Total_qty' => $totalQty,
                'status' => $status,
            ]);

            $rowNum++;
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
