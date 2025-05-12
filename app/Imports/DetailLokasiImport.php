<?php
namespace App\Imports;

use App\Models\Plant;
use App\Models\Area;
use App\Models\Rak;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DetailLokasiImport implements ToCollection, WithHeadingRow
{
    protected $logs = [];

    public function getLogs()
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $namaRak = trim($row['nama_rak'] ?? '');
            $namaArea = trim($row['nama_area'] ?? '');
            $namaPlan = trim($row['nama_plan'] ?? '');

            if (!$namaRak || !$namaArea || !$namaPlan) {
                $this->logs[] = "Baris ke-{$index} tidak lengkap: " . json_encode($row);
                continue;
            }

            // Cari atau buat plan
            $plan = Plant::firstOrCreate(['name' => $namaPlan]);

            // Cari atau buat area
            $area = Area::firstOrCreate([
                'nama_area' => $namaArea,
                'id_plan' => $plan->id,
            ]);

            // Cek duplikat rak
            $existing = Rak::where('nama_rak', $namaRak)->where('id_area', $area->id)->first();
            if ($existing) {
                $this->logs[] = "Duplikat: Rak {$namaRak} di area {$namaArea}";
                continue;
            }

            // Buat rak baru
            Rak::create([
                'nama_rak' => $namaRak,
                'id_area' => $area->id,
            ]);

            $this->logs[] = "Berhasil tambah: Rak {$namaRak} di area {$namaArea}";
        }
    }
}
