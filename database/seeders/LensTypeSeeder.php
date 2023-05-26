<?php

namespace Database\Seeders;

use App\Models\LensType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LensTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lens_types =[
            ['name'=>'SINGLE VISION'],
            ['name'=>'BIFOCAL'],
            ['name'=>'PROGRESSIVE'],
            ['name'=>'BIFOCAL ROUND TOP'],
        ];

        foreach ($lens_types as $type) {
            LensType::create([
                'name'=>$type['name'],
            ]);
        }
    }
}
