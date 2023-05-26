<?php

namespace Database\Seeders;

use App\Models\PhotoChromatics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChromaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chromatics =[
            ['name'=>'Clear'],
            ['name'=>'Photo'],
        ];

        foreach ($chromatics as $chromatic) {
            PhotoChromatics::create([
                'name'=>$chromatic['name'],
            ]);
        }
    }
}
