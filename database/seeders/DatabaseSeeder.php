<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(CategorySeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(CellSeeder::class);
        $this->call(IndexSeeder::class);
        $this->call(ChromaticSeeder::class);
        $this->call(CoatingSeeder::class);
        $this->call(LensTypeSeeder::class);
        $this->call(VillageSeeder::class);
        $this->call(UserSeeder::class);
    }
}
