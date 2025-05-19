<?php
namespace App\Imports;

use App\Models\Part;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Rak;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartsImport implements ToCollection, WithHeadingRow
{
    protected $logs = [];

    public function getLogs()
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $logMsg = "";

            // Log untuk data yang tidak lengkap
            if (isset($row['customer']) && !isset($row['plan'])) {
                $logMsg = "Kolom Plan tidak ditemukan pada baris: " . json_encode($row);
            }

            if (isset($row['customer']) && !isset($row['rak'])) {
                $logMsg = "Kolom Rak tidak ditemukan pada baris: " . json_encode($row);
            }
            if (isset($row['customer']) && !isset($row['kategori'])) {
                $logMsg = "Kolom Rak tidak ditemukan pada baris: " . json_encode($row);
            }

            // Simpan log ke array logs
            if ($logMsg) {
                $this->logs[] = $logMsg;
                continue; // Melewati baris jika ada error
            }

            // Proses bagian lainnya
            // Cek apakah data sudah ada
            $existingPart = Part::where('Inv_id', $row['inv_id'])
                ->where('Part_name', $row['part_name'])
                ->where('Part_number', $row['part_number'])
                ->first();

            if ($existingPart) {
                $this->logs[] = "Data duplikat ditemukan: INV ID: {$row['inv_id']}, Part Name: {$row['part_name']}";
                continue;
            }

            // Cek relasi lainnya
            $customer = Customer::where('username', $row['customer'])->first();
            $plant = Plant::where('name', $row['plan'])->first();
            $area = Area::where('nama_area', $row['area'])->first();
            $rak = Rak::where('nama_rak', $row['rak'])->first();
            $category = Category::where('name', $row['kategori'])->first();

            if (!$customer || !$plant || !$area || !$rak || !$category) {
                $this->logs[] = "Relasi tidak ditemukan pada baris: " . json_encode($row);
                continue;
            }

            // Jika semua ditemukan, simpan ke database
            $part = Part::create([
                'Inv_id' => $row['inv_id'],
                'Part_name' => $row['part_name'],
                'Part_number' => $row['part_number'],
                'id_customer' => $customer->id,
                'id_plan' => $plant->id,
                'id_area' => $area->id,
                'id_rak' => $rak->id,
                'id_category' => $category->id,
            ]);

            Package::create([
                'type_pkg' => $row['type_pkg'],
                'qty' => $row['qty_kanban'],
                'id_part' => $part->id,
            ]);

            // $this->logs[] = "Data berhasil disimpan: INV ID: {$row['inv_id']}, Part Name: {$row['part_name']}";
        }
    }
}
