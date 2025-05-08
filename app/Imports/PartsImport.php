<?php
namespace App\Imports;

use App\Models\Part;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Rak;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
// excel
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PartsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Log::info('Row data:', $row->toArray());

            // Ambil data relasi berdasarkan NAMA
            if (isset($row['customer'])) {
                $customer = Customer::where('username', $row['customer'])->first();
            } else {
                Log::warning('Kolom customer tidak ditemukan', ['row' => $row->toArray()]);
            }
            // plan
            if (isset($row['plan'])) {
                $plant = Plant::where('name', $row['plan'])->first();
            } else {
                Log::warning('Kolom Plan tidak ditemukan', ['row' => $row->toArray()]);
            }

            if (isset($row['area'])) {
                $area = Area::where('nama_area', $row['area'])->first();
            } else {
                Log::warning('Kolom Area tidak ditemukan', ['row' => $row->toArray()]);
            }

            if (isset($row['rak'])) {
                $rak = Rak::where('nama_rak', $row['rak'])->first();
            } else {
                Log::warning('Kolom Rak tidak ditemukan', ['row' => $row->toArray()]);
            }

            // Cek apakah data sudah ada di database untuk menghindari duplikat
            $existingPart = Part::where('inv_id', $row['inv_id'])
                ->where('part_name', $row['part_name'])
                ->where('part_number', $row['part_number'])
                ->first();

            if ($existingPart) {
                Log::warning('Data duplikat ditemukan, melewatkan baris ini', ['row' => $row->toArray()]);
                continue; // Melewati baris ini jika data sudah ada
            }

            // Jika semua relasi ditemukan dan tidak ada duplikat, simpan ke DB
            if ($customer && $plant && $area && $rak) {
                $part = Part::create([
                    'inv_id'       => $row['inv_id'],
                    'part_name'    => $row['part_name'],
                    'part_number'  => $row['part_number'],
                    'id_customer'  => $customer->id,
                    'id_plan'      => $plant->id,
                    'id_area'      => $area->id,
                    'id_rak'       => $rak->id,
                ]);

                Package::create([
                    'type_pkg' => $row['type_pkg'],
                    'qty'      => $row['qty_pkg'],
                    'id_part'  => $part->id,
                ]);
            } else {
                Log::warning('Import gagal, relasi tidak ditemukan', [
                    'customer' => $row['customer'],
                    'plant'    => $row['plan'],
                    'area'     => $row['area'],
                    'rak'      => $row['rak'],
                ]);
            }
        }
    }
}
