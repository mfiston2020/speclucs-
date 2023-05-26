<?php

namespace Database\Seeders;

use App\Models\PhotoIndex;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $indices =[
            ['id'=>'2','name'=>'1.56'],
            ['id'=>'3','name'=>'1.50'],
            ['id'=>'4','name'=>'1.74'],
            ['id'=>'5','name'=>'1.59'],
            ['id'=>'6','name'=>'1.61'],
            ['id'=>'7','name'=>'1.67'],
        ];

        foreach ($indices as $index) {
            PhotoIndex::create([
                'name'=>$index['name'],
            ]);
        }
    }
}
