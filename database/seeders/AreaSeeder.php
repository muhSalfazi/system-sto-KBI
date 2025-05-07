<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areaList = [
            // Plan 1
      ['name' => 'rak_a_a1_a25', 'label' => 'Area Rak A (A1-A25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_a_a26_a52', 'label' => 'Area Rak A (A26-A52)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_b_b1_b25', 'label' => 'Area Rak B (B1-B25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_b_b26_b54', 'label' => 'Area Rak B (B26-B54)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_c_c1_c25', 'label' => 'Area Rak C (C1-C25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_c_c26_c50', 'label' => 'Area Rak C (C26-C50)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_d_d1_d25', 'label' => 'Area Rak D (D1-D25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_d_d26_d50', 'label' => 'Area Rak D (D26-D50)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_e_e1_e25', 'label' => 'Area Rak E (E1-E25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_e_e26_e50', 'label' => 'Area Rak E (E26-E50)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_f_f1_f25', 'label' => 'Area Rak F (F1-F25)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_f_f26_f50', 'label' => 'Area Rak F (F26-F50)', 'category' => 'Childpart Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_packing', 'label' => 'Area Packanging YPC', 'category' => 'Packaging Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_packing', 'label' => 'Area Packanging Carton Box WH(2)', 'category' => 'Packaging Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_packing', 'label' => 'Area Packanging Carton Box WH(3)', 'category' => 'Packaging Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_finished_good_01', 'label' => 'Area Finished Good WH (11.1)', 'category' => 'Finished Good Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_finished_good_02', 'label' => 'Area Finished Good WH (11.2)', 'category' => 'Finished Good Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_finished_good_03', 'label' => 'Area Finished Good WH (11.3)', 'category' => 'Finished Good Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_finished_good_04', 'label' => 'Area Shutter FG, Prep MMKI (12.1)', 'category' => 'Finished Good Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_finished_good_05', 'label' => 'Area Shutter FG, Prep MMKI (12.2)', 'category' => 'Finished Good Area', 'plan' => 'Plan 1'],
      ['name' => 'rak_subcont_fg', 'label' => 'Area Subcont FG', 'category' => 'Area Subcont FG', 'plan' => 'Plan 1'],
      ['name' => 'rak_subcont_wip', 'label' => 'Area Subcont WIP', 'category' => 'Area Subcont WIP', 'plan' => 'Plan 1'],
      ['name' => 'rak_delivery', 'label' => 'Area Delivery', 'category' => 'Area Delivery', 'plan' => 'Plan 1'],
      ['name' => 'rak_material', 'label' => 'Area Material Transit', 'category' => 'Material Transit', 'plan' => 'Plan 1'],
      ['name' => 'rak_material', 'label' => 'Area Matrial WorkShop', 'category' => 'Material Transit', 'plan' => 'Plan 1'],
      ['name' => 'rak_shutter_01', 'label' => 'Area Shutter FG Fin Line 1-23 (16.1)', 'category' => 'Shutter FG Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_shutter_02', 'label' => 'Area Shutter FG Fin Line 1-23 (16.2)', 'category' => 'Shutter FG Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_shutter_03', 'label' => 'Area Shutter FG Fin Line 1-23 (16.3)', 'category' => 'Shutter FG Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_qc_wip', 'label' => 'Area WIP QC Office', 'category' => 'QC Office Room', 'plan' => 'Plan 1'],
      ['name' => 'rak_qc_fg', 'label' => 'Area FG QC Office', 'category' => 'QC Office Room', 'plan' => 'Plan 1'],
      ['name' => 'rak_manufacture_FG', 'label' => 'Area Office FG', 'category' => 'Manufacture Office', 'plan' => 'Plan 1'],
      ['name' => 'rak_manufacture_WIP', 'label' => 'Area Office WIP', 'category' => 'Manufacture Office', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_fin_01', 'label' => 'Area Produksi (Finishing) WIP', 'category' => 'WIP Lin Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_childpart_fin_01', 'label' => 'Area Childpart Fin Line (1-10)', 'category' => 'Childpart Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_childpart_fin_02', 'label' => 'Area Childpart Fin Line (11-20)', 'category' => 'Childpart Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_childpart_fin_01', 'label' => 'Area Childpart Fin Line (21-30)', 'category' => 'Childpart Fin', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_shutter_01', 'label' => 'Area WIP Shutter Molding 1-30 (21.1)', 'category' => 'WIP Shutter Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_shutter_02', 'label' => 'Area WIP Shutter Molding 1-30 (21.2)', 'category' => 'WIP Shutter Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_shutter_03', 'label' => 'Area WIP Shutter Molding 32-59 (21.3)', 'category' => 'WIP Shutter Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_shutter_04', 'label' => 'Area WIP Shutter Molding 32-59 (21.4)', 'category' => 'WIP Shutter Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_pianica_01', 'label' => 'Area WIP Pianca (23.1)', 'category' => 'WIP Pianica', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_pianica_02', 'label' => 'Area WIP Pianca (23.2)', 'category' => 'WIP Pianica', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_wh2_01', 'label' => 'Area WIP WH 2 (24.1)', 'category' => 'WIP WH 2', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_wh2_02', 'label' => 'Area WIP WH 2 (24.2)', 'category' => 'WIP WH 2', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_molding', 'label' => 'Area WIP Molding', 'category' => 'WIP Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_material_molding_01', 'label' => 'Area Material Line Molding V', 'category' => 'Material Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_material_molding_02', 'label' => 'Area Material Line Molding F', 'category' => 'Material Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_material_molding_03', 'label' => 'Area Material Line Funsai Mix', 'category' => 'Material Molding', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_daisha_01', 'label' => 'Area WIP Rak Daisha (27.1)', 'category' => 'WIP Rak Daisha', 'plan' => 'Plan 1'],
      ['name' => 'rak_wip_daisha_02', 'label' => 'Area WIP Rak Daisha (27.2)', 'category' => 'WIP Rak Daisha', 'plan' => 'Plan 1'],
      ['name' => 'rak_service_part', 'label' => 'Area SPD', 'category' => 'Area Service Part', 'plan' => 'Plan 1'],
      ['name' => 'rak_Off_Deliver', 'label' => 'Area Cut Off Delivery', 'category' => 'Cut Off Delivery', 'plan' => 'Plan 1'],
      // Plan 2
      ['name' => 'wip_line_blowmolding', 'label' => 'Area Wip Line Blowmolding', 'category' => 'Wip Line Blowmolding', 'plan' => 'Plan 2'],
      ['name' => 'material_line_blowmolding', 'label' => 'Material Line Blowmolding', 'category' => 'Material Line Blowmolding', 'plan' => 'Plan 2'],
      ['name' => 'wip_shutter_spoiler', 'label' => 'WIP Shutter Spoiler', 'category' => 'WIP Shutter Spoiler', 'plan' => 'Plan 2'],
      ['name' => 'wip_sanding_area', 'label' => 'WIP Sanding Area', 'category' => 'WIP Sanding Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_area_ng_spoiler', 'label' => 'FG Area NG Spoiler', 'category' => 'FG Area NG Spoiler', 'plan' => 'Plan 2'],
      ['name' => 'wip_shutter_1', 'label' => 'WIP Shutter 1', 'category' => 'WIP Shutter', 'plan' => 'Plan 2'],
      ['name' => 'wip_shutter_2', 'label' => 'WIP Shutter 2', 'category' => 'WIP Shutter', 'plan' => 'Plan 2'],
      ['name' => 'material_warehouse', 'label' => 'Material Warehouse', 'category' => 'Material Warehouse', 'plan' => 'Plan 2'],
      ['name' => 'packaging_wh', 'label' => 'Packaging WH', 'category' => 'Packaging WH', 'plan' => 'Plan 2'],
      ['name' => 'wip_ducting_wh', 'label' => 'WIP Ducting WH', 'category' => 'WIP Ducting WH', 'plan' => 'Plan 2'],
      ['name' => 'finishing_line_1_9', 'label' => 'Finishing Line 1-9', 'category' => 'Finishing Line', 'plan' => 'Plan 2'],
      ['name' => 'finishing_line_10_18', 'label' => 'Finishing Line 10-18', 'category' => 'Finishing Line', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_a', 'label' => 'Childpart Rack - A', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_b', 'label' => 'Childpart Rack - B', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_c', 'label' => 'Childpart Rack - C', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_d', 'label' => 'Childpart Rack - D', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_e', 'label' => 'Childpart Rack - E', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_f', 'label' => 'Childpart Rack - F', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_g', 'label' => 'Childpart Rack - G', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_h', 'label' => 'Childpart Rack - H', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_i', 'label' => 'Childpart Rack - I', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_j', 'label' => 'Childpart Rack - J', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_pallet_area', 'label' => 'Childpart Pallet Area', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_k', 'label' => 'Childpart Rack - K', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_l', 'label' => 'Childpart Rack - L', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_m', 'label' => 'Childpart Rack - M', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_na', 'label' => 'Childpart Rack - NA', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'childpart_rack_nb', 'label' => 'Childpart Rack - NB', 'category' => 'Childpart Rack', 'plan' => 'Plan 2'],
      ['name' => 'receiving_cpart_temporary_area', 'label' => 'Receiving Cpart & Temporary Area', 'category' => 'Receiving Cpart & Temporary Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_shutter_a', 'label' => 'FG Shutter A', 'category' => 'FG Shutter', 'plan' => 'Plan 2'],
      ['name' => 'fg_shutter_b', 'label' => 'FG Shutter B', 'category' => 'FG Shutter', 'plan' => 'Plan 2'],
      ['name' => 'wip_inoac', 'label' => 'WIP Inoac', 'category' => 'WIP Inoac', 'plan' => 'Plan 2'],
      ['name' => 'fg_area_prepare_denso', 'label' => 'FG Area Prepare Denso', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_palet', 'label' => 'FG Palet', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_export', 'label' => 'FG Export +', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_prepare_adm', 'label' => 'FG Prepare ADM', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_prepare_spd', 'label' => 'FG Prepare SPD', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_dmia_wh', 'label' => 'FG DMIA WH+', 'category' => 'FG Area', 'plan' => 'Plan 2'],
      ['name' => 'fg_injection_area', 'label' => 'FG Injection Area', 'category' => 'FG Injection Area', 'plan' => 'Plan 2'],
      ['name' => 'pe_room', 'label' => 'PE Room', 'category' => 'PE Room', 'plan' => 'Plan 2'],
      ['name' => 'area_crusher_material_injection', 'label' => 'Area Crusher & Material Injection', 'category' => 'Area Crusher & Material Injection', 'plan' => 'Plan 2'],
      ['name' => 'delivery_area', 'label' => 'Delivery Area +', 'category' => 'Delivery Area', 'plan' => 'Plan 2'],
      ['name' => 'lantai_2', 'label' => 'Lantai-2', 'category' => 'Lantai-2', 'plan' => 'Plan 2'],
      ['name' => 'dojo_area', 'label' => 'DOJO Area', 'category' => 'DOJO Area', 'plan' => 'Plan 2']
        ];

        foreach ($areaList as $area) {
            $planId = DB::table('tbl_plan')->where('name', $area['plan'])->value('id');
            DB::table('tbl_area')->updateOrInsert(
                ['nama_area' => $area['label'], 'id_plan' => $planId]
            );
        }
    }
}
