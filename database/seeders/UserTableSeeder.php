<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRecords=[[
            'id'=>'1','fname'=>'Sam','lname'=>'Masika','username'=>'Samchard','phone'=>'255714439960','email'=>'masika@gmail.com','password'=>'$2a$12$FXmpccnsF8coowwQlHdOZOnPDKDyCmXlk.ksiRBxdFKUVTBv68gYC',
           ], 
        ];
        User::insert($adminRecords);
    }
}
