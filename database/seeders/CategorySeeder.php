<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            "Finished Good",
            "Wip",
            "Packaging",
            "ChildPart",
            "Raw Material",
        ];

        foreach ($categories as $category) {
            DB::table('tbl_category')->updateOrInsert(['name' => $category]);
        }
    }
}
