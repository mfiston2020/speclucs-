<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories =   [
            ['name'=>'Lens'],
            ['name'=>'Frame'],
            ['name'=>'Sunglass'],
            ['name'=>'Hardcases'],
            ['name'=>'Cleaning Kits'],
            ['name'=>'Reading Glasses'],
            ['name'=>'Sunglasses'],
        ];

        foreach ($categories as $key => $category) {
            Category::create([
                'name'=>$category['name'],
            ]);
        }
    }
}
