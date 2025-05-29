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
    protected $processedKeys = [];

    public function getLogs()
    {
        return $this->logs;
    }

    public function collection(Collection $rows)
    {
        $customers = Customer::all()->keyBy('username');
        $plants = Plant::all()->keyBy('name');
        $categories = Category::all()->keyBy('name');

        foreach ($rows as $index => $row) {
            if (
                empty($row['inv_id']) ||
                empty($row['part_name']) ||
                empty($row['part_number']) ||
                empty($row['customer']) ||
                empty($row['plan']) ||
                empty($row['area']) ||
                empty($row['kategori'])
            ) {
                if (collect($row)->filter()->isEmpty())
                    continue;
                $this->logs[] = "Baris " . ($index + 2) . " tidak lengkap.";
                continue;
            }

            $key = $row['inv_id'] . '|' . $row['customer'];
            if (in_array($key, $this->processedKeys))
                continue;
            $this->processedKeys[] = $key;

            $customer = $customers[$row['customer']] ?? null;
            $plant = $plants[$row['plan']] ?? null;
            $category = $categories[$row['kategori']] ?? null;

            if (!$customer || !$plant || !$category) {
                $this->logs[] = "Baris " . ($index + 2) . ": Customer/Plant/Category tidak ditemukan.";
                continue;
            }

            $area = Area::firstOrCreate([
                'id_plan' => $plant->id,
                'nama_area' => $row['area'],
            ]);

            $part = Part::firstOrNew([
                'Inv_id' => $row['inv_id'],
                'id_customer' => $customer->id,
            ]);
            $part->fill([
                'Part_name' => $row['part_name'],
                'Part_number' => $row['part_number'],
                'id_plan' => $plant->id,
                'id_area' => $area->id,
                'id_category' => $category->id,
            ]);
            $part->save();

            if (!empty($row['type_pkg']) && !empty($row['qty_kanban'])) {
                $package = Package::firstOrNew(['id_part' => $part->id]);
                $package->fill([
                    'type_pkg' => $row['type_pkg'],
                    'qty' => $row['qty_kanban'],
                ])->save();
            }
        }
    }

}
