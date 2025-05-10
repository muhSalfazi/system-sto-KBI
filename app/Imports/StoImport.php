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
    protected $logs = [];

    public function getLogs()
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

            // Cari part berdasarkan INV ID
            $part = Part::where('Inv_id', $row['inv_id'])->first();

            if (!$part) {
                $this->logs[] = "Baris $rowNumber: Part tidak ditemukan (INV ID: {$row['inv_id']}).";
                continue;
            }

            // Cari kategori berdasarkan nama
            $category = Category::where('name', $row['kategori'])->first();

            if (!$category) {
                $this->logs[] = "Baris $rowNumber: Kategori tidak ditemukan ({$row['kategori']}).";
                continue;
            }

            // Periksa apakah data sudah ada di database dan apakah periode bulan ini
            $existingSto = Inventory::where('id_part', $part->id)
                ->where('id_category', $category->id)
                ->first();

            if ($existingSto) {
                // Periksa apakah data sudah ada dalam periode bulan ini
                $createdAt = Carbon::parse($existingSto->created_at);
                $now = Carbon::now();
                $diffInMonths = $createdAt->diffInMonths($now);

                if ($diffInMonths < 1) {
                    // Jika sudah ada dan masih dalam periode bulan ini, maka lewati
                    $this->logs[] = "Baris $rowNumber: Sudah ada di database untuk bulan ini (INV ID: {$row['inv_id']}, Part Name: {$row['part_name']}).";
                    continue;  // Data duplikat dalam bulan yang sama
                }
            }

            // Jika data belum ada atau lebih dari satu bulan, simpan ke database
            Inventory::create([
                'id_part' => $part->id,
                'id_category' => $category->id,
                'plan_stock' => $row['plan_stock'],
                'status' => strtoupper($row['status']) === 'OK' ? 'OK' : 'NG',
            ]);

            // Log keberhasilan
            $this->logs[] = "Baris $rowNumber: Data berhasil diimpor (INV ID: {$row['inv_id']})";
        }
    }
}
