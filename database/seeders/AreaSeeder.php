<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areaList = $this->getAreaList();

        foreach ($areaList as $area) {
            $planId = DB::table('tbl_plan')->where('name', $area['plan'])->value('id');

            if (!$planId) {
                continue; // skip jika plan tidak ditemukan
            }

            DB::table('tbl_area')->updateOrInsert(
                [
                    'nama_area' => $area['label'],
                    'id_plan' => $planId
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }

    private function getAreaList(): array
    {
        return [
            ['label' => 'Area Rak A (A1-A25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak A (A26-A52)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak B (B1-B25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak B (B26-B54)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak C (C1-C25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak C (C26-C50)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak D (D1-D25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak D (D26-D50)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak E (E1-E25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak E (E26-E50)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak F (F1-F25)', 'plan' => 'KBI 1'],
            ['label' => 'Area Rak F (F26-F50)', 'plan' => 'KBI 1'],
            ['label' => 'Area Packanging YPC', 'plan' => 'KBI 1'],
            ['label' => 'Area Packanging Carton Box WH(2)', 'plan' => 'KBI 1'],
            ['label' => 'Area Packanging Carton Box WH(3)', 'plan' => 'KBI 1'],
            ['label' => 'Area Finished Good WH (11.1)', 'plan' => 'KBI 1'],
            ['label' => 'Area Finished Good WH (11.2)', 'plan' => 'KBI 1'],
            ['label' => 'Area Finished Good WH (11.3)', 'plan' => 'KBI 1'],
            ['label' => 'Area Shutter FG, Prep MMKI (12.1)', 'plan' => 'KBI 1'],
            ['label' => 'Area Shutter FG, Prep MMKI (12.2)', 'plan' => 'KBI 1'],
            ['label' => 'Area Subcont FG', 'plan' => 'KBI 1'],
            ['label' => 'Area Subcont WIP', 'plan' => 'KBI 1'],
            ['label' => 'Area Delivery', 'plan' => 'KBI 1'],
            ['label' => 'Area Material Transit', 'plan' => 'KBI 1'],
            ['label' => 'Area Matrial WorkShop', 'plan' => 'KBI 1'],

            // KBI 2
            ['label' => 'Area Wip Line Blowmolding', 'plan' => 'KBI 2'],
            ['label' => 'Material Line Blowmolding', 'plan' => 'KBI 2'],
            ['label' => 'WIP Shutter Spoiler', 'plan' => 'KBI 2'],
            ['label' => 'WIP Sanding Area', 'plan' => 'KBI 2'],
            ['label' => 'FG Area NG Spoiler', 'plan' => 'KBI 2'],
            ['label' => 'WIP Shutter 1', 'plan' => 'KBI 2'],
            ['label' => 'WIP Shutter 2', 'plan' => 'KBI 2'],
            ['label' => 'Material Warehouse', 'plan' => 'KBI 2'],
            ['label' => 'Packaging WH', 'plan' => 'KBI 2'],
            ['label' => 'WIP Ducting WH', 'plan' => 'KBI 2'],
            ['label' => 'Finishing Line 1-9', 'plan' => 'KBI 2'],
            ['label' => 'Finishing Line 10-18', 'plan' => 'KBI 2'],

            // Childpart
            ['label' => 'Childpart Rack - A', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - B', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - C', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - D', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - E', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - F', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - G', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - H', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - I', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - J', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Pallet Area', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - K', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - L', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - M', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - NA', 'plan' => 'KBI 2'],
            ['label' => 'Childpart Rack - NB', 'plan' => 'KBI 2'],
            ['label' => 'Receiving Cpart & Temporary Area', 'plan' => 'KBI 2'],

            // FG
            ['label' => 'FG Shutter A', 'plan' => 'KBI 2'],
            ['label' => 'FG Shutter B', 'plan' => 'KBI 2'],
            ['label' => 'WIP Inoac', 'plan' => 'KBI 2'],
            ['label' => 'FG Area Prepare Denso', 'plan' => 'KBI 2'],
            ['label' => 'FG Palet', 'plan' => 'KBI 2'],
            ['label' => 'FG Export +', 'plan' => 'KBI 2'],
            ['label' => 'FG Prepare ADM', 'plan' => 'KBI 2'],
            ['label' => 'FG Prepare SPD', 'plan' => 'KBI 2'],
            ['label' => 'FG DMIA WH+', 'plan' => 'KBI 2'],
            ['label' => 'FG Injection Area', 'plan' => 'KBI 2'],

            // Lain-lain
            ['label' => 'PE Room', 'plan' => 'KBI 2'],
            ['label' => 'Area Crusher & Material Injection', 'plan' => 'KBI 2'],
            ['label' => 'Delivery Area +', 'plan' => 'KBI 2'],
            ['label' => 'Lantai-2', 'plan' => 'KBI 2'],
            ['label' => 'DOJO Area', 'plan' => 'KBI 2'],
        ];
    }
}
