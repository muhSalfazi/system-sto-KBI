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
            // Cek kolom penting
            if (!isset($row['inv_id'], $row['part_name'], $row['part_number'], $row['customer'], $row['plan'], $row['area'], $row['rak'], $row['kategori'], $row['type_pkg'], $row['qty_kanban'])) {
                $this->logs[] = "Baris tidak lengkap: " . json_encode($row);
                continue;
            }
            // Cek duplikat berdasarkan kombinasi Inv_id dan Customer
            $customer = Customer::where('username', $row['customer'])->first();
            if (!$customer) {
                $this->logs[] = "Customer tidak ditemukan: {$row['customer']}";
                continue;
            }

            $existingPart = Part::where('Inv_id', $row['inv_id'])
                ->where('id_customer', $customer->id)
                ->first();

            if ($existingPart) {
                $this->logs[] = "Duplikat: INV ID {$row['inv_id']} untuk Customer {$row['customer']}";
                continue;
            }

            // Ambil relasi lainnya
            $customer = Customer::where('username', $row['customer'])->first();
            $plant = Plant::where('name', $row['plan'])->first();
            $category = Category::where('name', $row['kategori'])->first();

            if (!$customer || !$plant || !$category) {
                $this->logs[] = "Relasi tidak ditemukan (Customer/Plant/Kategori): " . json_encode($row);
                continue;
            }

            // Create or Get Area
            $area = Area::firstOrCreate([
                'id_plan' => $plant->id,
                'nama_area' => $row['area'],
            ]);

            // Create or Get Rak
            $rak = Rak::firstOrCreate([
                'id_area' => $area->id,
                'nama_rak' => $row['rak'],
            ]);

            // Buat Part
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

            // Buat Package
            Package::create([
                'type_pkg' => $row['type_pkg'],
                'qty' => $row['qty_kanban'],
                'id_part' => $part->id,
            ]);

            $this->logs[] = "Berhasil simpan: INV ID {$row['inv_id']}";
        }
    }
}
