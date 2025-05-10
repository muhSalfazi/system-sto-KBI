<?php
namespace App\Imports;

use App\Models\DailyStockLog;
use App\Models\Inventory;
use App\Models\Part;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Untuk menggunakan header
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DailyStockImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * Mengimpor data dari file Excel atau CSV
     */
    public function model(array $row)
    {
        // Mendapatkan Inv_id dan Total_qty dari baris
        $invId = $row['inv_id'];  // Pastikan kolom header di Excel atau CSV sesuai dengan ini
        $totalQty = (int) $row['total_qty'];  // Sesuaikan dengan nama kolom di file

        // Mencari Part berdasarkan Inv_id
        $part = Part::where('Inv_id', $invId)->first();

        if ($part) {
            // Mencari Inventory terkait berdasarkan Part
            $inventory = Inventory::where('id_part', $part->id)->first();

            if ($inventory) {
                // Memperbarui act_stock di Inventory
                $inventory->act_stock += $totalQty;
                $inventory->updated_at = now();
                $inventory->save();

                // Menambahkan log perubahan stock harian
                return new DailyStockLog([
                    'id_inventory' => $inventory->id,
                    'prepared_by' => auth()->id(),
                    'Total_qty' => $totalQty,
                ]);
            }
        }

        // Jika tidak ada data yang ditemukan, kita tidak memprosesnya
        return null;
    }

    /**
     * Batch inserts untuk impor besar
     */
    public function batchSize(): int
    {
        return 1000;  // Menentukan jumlah baris per batch insert
    }

    /**
     * Chunk reading untuk mengurangi penggunaan memori
     */
    public function chunkSize(): int
    {
        return 1000;  // Menentukan jumlah baris per chunk
    }
}
