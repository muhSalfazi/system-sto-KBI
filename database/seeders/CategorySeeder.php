<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Childpart Area',
            'Packaging Area',
            'Finished Good Area',
            'Shutter FG Fin',
            'QC Office Room',
            'Manufacture Office',
            'WIP Lin Fin',
            'Childpart Fin',
            'WIP Shutter Molding',
            'WIP Pianica',
            'WIP WH 2',
            'WIP Molding',
            'Material Molding',
            'WIP Rak Daisha',
            'Area Service Part',
            'Cut Off Delivery',
            'Wip Line Blowmolding',
            'Material Line Blowmolding',
            'WIP Shutter Spoiler',
            'WIP Sanding Area',
            'FG Area NG Spoiler',
            'WIP Shutter',
            'Material Warehouse',
            'Packaging WH',
            'WIP Ducting WH',
            'Finishing Line',
            'Childpart Rack',
            'Receiving Cpart & Temporary Area',
            'FG Shutter',
            'WIP Inoac',
            'FG Area',
            'FG Injection Area',
            'PE Room',
            'Area Crusher & Material Injection',
            'Delivery Area',
            'Lantai-2',
            'DOJO Area'
        ];

        foreach ($categories as $category) {
            DB::table('tbl_category')->updateOrInsert(['name' => $category]);
        }
    }
}
