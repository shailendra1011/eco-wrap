<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('categories')->insert([
        //     [
        //         'id'        =>  1,
        //         'category_name'      =>  'Food',
        //         'category_name_es'      =>  'Comida',
        //         'created_at' =>  Carbon::now()->toDateTimeString(),
        //         'updated_at' =>  Carbon::now()->toDateTimeString(),
        //     ],
        //     [
        //         'id'        =>  2,
        //         'category_name'      =>  'Pharmacy',
        //         'category_name_es'      =>  'Farmacia',
        //         'created_at' =>  Carbon::now()->toDateTimeString(),
        //         'updated_at' =>  Carbon::now()->toDateTimeString(),
        //     ],
        //     [
        //         'id'        =>  3,
        //         'category_name'      =>  'Product',
        //         'category_name_es'      =>  'Producto',
        //         'created_at' =>  Carbon::now()->toDateTimeString(),
        //         'updated_at' =>  Carbon::now()->toDateTimeString(),
        //     ]
        // ]);
    }
}
