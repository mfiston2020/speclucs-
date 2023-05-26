<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces =   [
            ['name'=>'KIGALI'],
            ['name'=>'SOUTHERN'],
            ['name'=>'WESTERN'],
            ['name'=>'NORTHERN'],
            ['name'=>'EASTERN'],
        ];

        foreach ($provinces as $key => $province) {
            Province::create([
                'name'=>$province['name'],
            ]);
        }
    }
}
