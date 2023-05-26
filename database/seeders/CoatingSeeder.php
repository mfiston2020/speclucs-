<?php

namespace Database\Seeders;

use App\Models\PhotoCoating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coatings =[
            ['id'=>'2','name'=>'HC'],
            ['id'=>'3','name'=>'HMC'],
            ['id'=>'6','name'=>'UC'],
            ['id'=>'7','name'=>'BLUE CUT HMC'],
            ['id'=>'8','name'=>'BLUE CUT HC'],
        ];

        foreach ($coatings as $coating) {
            PhotoCoating::create([
                'name'=>$coating['name'],
            ]);
        }
    }
}
