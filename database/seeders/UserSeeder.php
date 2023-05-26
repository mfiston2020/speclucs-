<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users  =   [
            ['company_id'=>'0','name'=>'Fiston MUNYAMPETA', 'role'=>'admin','status'=>'active','password'=>'$2y$10$k/73t2TQZD4z/C4wISzEDu.lVwfZ2Whb3452yKUxx8d/oES0wrjBq','email'=>'admin@speclucs.com','phone'=>'0786416476'],
            ['company_id'=>'1','name'=>'Fiston MUNYAMPETA', 'role'=>'manager','status'=>'active','password'=>'$2y$10$5.mmiUNHah/ejd.Ot2lTJeMWkTv35ufcci/YxIdE9uY/1ilJBJPRO','email'=>'fiston@stock.com','phone'=>'0786416476']
        ];

        foreach ($users as $user) {
            User::create([
                'company_id'=>$user['company_id'],
                'name'=>$user['name'],
                'email'=>$user['email'],
                'role'=>$user['role'],
                'status'=>$user['status'],
                'password'=>$user['password'],
                'phone'=>$user['phone'],
            ]);
        }
    }
}
