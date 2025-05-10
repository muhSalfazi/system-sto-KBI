<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areaList = [
                // Plan 1 -> KBI 1
                ['name' => 'rak_a_a1_a25', 'label' => 'Area Rak A (A1-A25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_a_a26_a52', 'label' => 'Area Rak A (A26-A52)', 'plan' => 'KBI 1'],
                ['name' => 'rak_b_b1_b25', 'label' => 'Area Rak B (B1-B25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_b_b26_b54', 'label' => 'Area Rak B (B26-B54)', 'plan' => 'KBI 1'],
                ['name' => 'rak_c_c1_c25', 'label' => 'Area Rak C (C1-C25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_c_c26_c50', 'label' => 'Area Rak C (C26-C50)', 'plan' => 'KBI 1'],
                ['name' => 'rak_d_d1_d25', 'label' => 'Area Rak D (D1-D25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_d_d26_d50', 'label' => 'Area Rak D (D26-D50)', 'plan' => 'KBI 1'],
                ['name' => 'rak_e_e1_e25', 'label' => 'Area Rak E (E1-E25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_e_e26_e50', 'label' => 'Area Rak E (E26-E50)', 'plan' => 'KBI 1'],
                ['name' => 'rak_f_f1_f25', 'label' => 'Area Rak F (F1-F25)', 'plan' => 'KBI 1'],
                ['name' => 'rak_f_f26_f50', 'label' => 'Area Rak F (F26-F50)', 'plan' => 'KBI 1'],
                ['name' => 'rak_packing', 'label' => 'Area Packanging YPC', 'plan' => 'KBI 1'],
                ['name' => 'rak_packing', 'label' => 'Area Packanging Carton Box WH(2)', 'plan' => 'KBI 1'],
                ['name' => 'rak_packing', 'label' => 'Area Packanging Carton Box WH(3)', 'plan' => 'KBI 1'],
                ['name' => 'rak_finished_good_01', 'label' => 'Area Finished Good WH (11.1)', 'plan' => 'KBI 1'],
                ['name' => 'rak_finished_good_02', 'label' => 'Area Finished Good WH (11.2)', 'plan' => 'KBI 1'],
                ['name' => 'rak_finished_good_03', 'label' => 'Area Finished Good WH (11.3)', 'plan' => 'KBI 1'],
                ['name' => 'rak_finished_good_04', 'label' => 'Area Shutter FG, Prep MMKI (12.1)', 'plan' => 'KBI 1'],
                ['name' => 'rak_finished_good_05', 'label' => 'Area Shutter FG, Prep MMKI (12.2)', 'plan' => 'KBI 1'],
                ['name' => 'rak_subcont_fg', 'label' => 'Area Subcont FG', 'plan' => 'KBI 1'],
                ['name' => 'rak_subcont_wip', 'label' => 'Area Subcont WIP', 'plan' => 'KBI 1'],
                ['name' => 'rak_delivery', 'label' => 'Area Delivery', 'plan' => 'KBI 1'],
                ['name' => 'rak_material', 'label' => 'Area Material Transit', 'plan' => 'KBI 1'],
                ['name' => 'rak_material', 'label' => 'Area Matrial WorkShop', 'plan' => 'KBI 1'],
                // Plan 2 -> KBI 2
                ['name' => 'wip_line_blowmolding', 'label' => 'Area Wip Line Blowmolding', 'plan' => 'KBI 2'],
                ['name' => 'material_line_blowmolding', 'label' => 'Material Line Blowmolding', 'plan' => 'KBI 2'],
                ['name' => 'wip_shutter_spoiler', 'label' => 'WIP Shutter Spoiler', 'plan' => 'KBI 2'],
                ['name' => 'wip_sanding_area', 'label' => 'WIP Sanding Area', 'plan' => 'KBI 2'],
                ['name' => 'fg_area_ng_spoiler', 'label' => 'FG Area NG Spoiler', 'plan' => 'KBI 2'],
                ['name' => 'wip_shutter_1', 'label' => 'WIP Shutter 1', 'plan' => 'KBI 2'],
                ['name' => 'wip_shutter_2', 'label' => 'WIP Shutter 2', 'plan' => 'KBI 2'],
                ['name' => 'material_warehouse', 'label' => 'Material Warehouse', 'plan' => 'KBI 2'],
                ['name' => 'packaging_wh', 'label' => 'Packaging WH', 'plan' => 'KBI 2'],
                ['name' => 'wip_ducting_wh', 'label' => 'WIP Ducting WH', 'plan' => 'KBI 2'],
                ['name' => 'finishing_line_1_9', 'label' => 'Finishing Line 1-9', 'plan' => 'KBI 2'],
                ['name' => 'finishing_line_10_18', 'label' => 'Finishing Line 10-18', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_a', 'label' => 'Childpart Rack - A', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_b', 'label' => 'Childpart Rack - B', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_c', 'label' => 'Childpart Rack - C', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_d', 'label' => 'Childpart Rack - D', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_e', 'label' => 'Childpart Rack - E', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_f', 'label' => 'Childpart Rack - F', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_g', 'label' => 'Childpart Rack - G', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_h', 'label' => 'Childpart Rack - H', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_i', 'label' => 'Childpart Rack - I', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_j', 'label' => 'Childpart Rack - J', 'plan' => 'KBI 2'],
                ['name' => 'childpart_pallet_area', 'label' => 'Childpart Pallet Area', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_k', 'label' => 'Childpart Rack - K', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_l', 'label' => 'Childpart Rack - L', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_m', 'label' => 'Childpart Rack - M', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_na', 'label' => 'Childpart Rack - NA', 'plan' => 'KBI 2'],
                ['name' => 'childpart_rack_nb', 'label' => 'Childpart Rack - NB', 'plan' => 'KBI 2'],
                ['name' => 'receiving_cpart_temporary_area', 'label' => 'Receiving Cpart & Temporary Area', 'plan' => 'KBI 2'],
                ['name' => 'fg_shutter_a', 'label' => 'FG Shutter A', 'plan' => 'KBI 2'],
                ['name' => 'fg_shutter_b', 'label' => 'FG Shutter B', 'plan' => 'KBI 2'],
                ['name' => 'wip_inoac', 'label' => 'WIP Inoac', 'plan' => 'KBI 2'],
                ['name' => 'fg_area_prepare_denso', 'label' => 'FG Area Prepare Denso', 'plan' => 'KBI 2'],
                ['name' => 'fg_palet', 'label' => 'FG Palet', 'plan' => 'KBI 2'],
                ['name' => 'fg_export', 'label' => 'FG Export +', 'plan' => 'KBI 2'],
                ['name' => 'fg_prepare_adm', 'label' => 'FG Prepare ADM', 'plan' => 'KBI 2'],
                ['name' => 'fg_prepare_spd', 'label' => 'FG Prepare SPD', 'plan' => 'KBI 2'],
                ['name' => 'fg_dmia_wh', 'label' => 'FG DMIA WH+', 'plan' => 'KBI 2'],
                ['name' => 'fg_injection_area', 'label' => 'FG Injection Area', 'plan' => 'KBI 2'],
                ['name' => 'pe_room', 'label' => 'PE Room', 'plan' => 'KBI 2'],
                ['name' => 'area_crusher_material_injection', 'label' => 'Area Crusher & Material Injection', 'plan' => 'KBI 2'],
                ['name' => 'delivery_area', 'label' => 'Delivery Area +', 'plan' => 'KBI 2'],
                ['name' => 'lantai_2', 'label' => 'Lantai-2', 'plan' => 'KBI 2'],
                ['name' => 'dojo_area', 'label' => 'DOJO Area', 'plan' => 'KBI 2']
            ];


        foreach ($areaList as $area) {
            $planId = DB::table('tbl_plan')->where('name', $area['plan'])->value('id');
            DB::table('tbl_area')->updateOrInsert(
                ['nama_area' => $area['label'], 'id_plan' => $planId],
                [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]

            );
        }
    }
}
