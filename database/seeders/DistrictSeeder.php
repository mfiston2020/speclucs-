<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts  =   [
            ['id'=>'101',   'name'=>'Nyarugenge' ,'province_id'=>'1'],
            ['id'=>'102',   'name'=>'Gasabo'     ,'province_id'=>'1'],
            ['id'=>'103',   'name'=>'Kicukiro'   ,'province_id'=>'1'],
            ['id'=>'201',   'name'=>'Nyanza'     ,'province_id'=>'2'],
            ['id'=>'202',   'name'=>'Gisagara'   ,'province_id'=>'2'],
            ['id'=>'203',   'name'=>'Nyaruguru'  ,'province_id'=>'2'],
            ['id'=>'204',   'name'=>'Huye'       ,'province_id'=>'2'],
            ['id'=>'205',   'name'=>'Nyamagabe'  ,'province_id'=>'2'],
            ['id'=>'206',   'name'=>'Ruhango'    ,'province_id'=>'2'],
            ['id'=>'207',   'name'=>'Muhanga'    ,'province_id'=>'2'],
            ['id'=>'208',   'name'=>'Kamonyi'    ,'province_id'=>'2'],
            ['id'=>'301',   'name'=>'Karongi'    ,'province_id'=>'3'],
            ['id'=>'302',   'name'=>'Rutsiro'    ,'province_id'=>'3'],
            ['id'=>'303',   'name'=>'Rubavu'     ,'province_id'=>'3'],
            ['id'=>'304',   'name'=>'Nyabihu'    ,'province_id'=>'3'],
            ['id'=>'305',   'name'=>'Ngororero'  ,'province_id'=>'3'],
            ['id'=>'306',   'name'=>'Rusizi'     ,'province_id'=>'3'],
            ['id'=>'307',   'name'=>'Nyamasheke' ,'province_id'=>'3'],
            ['id'=>'401',   'name'=>'Rulindo'    ,'province_id'=>'4'],
            ['id'=>'402',   'name'=>'Gakenke'    ,'province_id'=>'4'],
            ['id'=>'403',   'name'=>'Musanze'    ,'province_id'=>'4'],
            ['id'=>'404',   'name'=>'Burera'     ,'province_id'=>'4'],
            ['id'=>'405',   'name'=>'Gicumbi'    ,'province_id'=>'4'],
            ['id'=>'501',   'name'=>'Rwamagana'  ,'province_id'=>'5'],
            ['id'=>'502',   'name'=>'Nyagatare'  ,'province_id'=>'5'],
            ['id'=>'503',   'name'=>'Gatsibo'    ,'province_id'=>'5'],
            ['id'=>'504',   'name'=>'Kayonza'    ,'province_id'=>'5'],
            ['id'=>'505',   'name'=>'Kirehe'     ,'province_id'=>'5'],
            ['id'=>'506',   'name'=>'Ngoma'      ,'province_id'=>'5'],
            ['id'=>'507',   'name'=>'Bugesera'   ,'province_id'=>'5'],
        ];


        foreach ($districts as $district) {
            District::create([
                'id'=>$district['id'],
                'name'=>$district['name'],
                'province_id'=>$district['province_id'],
            ]);
        }
    }
}
