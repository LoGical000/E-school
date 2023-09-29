<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRegistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user=User::create([
            'email' => "AdminOfSchool22@schoolname.com",
            'password' => bcrypt('100123456'),
            'role'=>1
        ]);


        DB::table('admins')->insert([
            'first_name'=>'first admin',
            'last_name'=>'ll',
            'user_id'=>$user->id
        ]);

        $user2=User::create([
            'email' => "omar@schoolname.com",
            'password' => bcrypt('123456'),
            'role'=>1
        ]);


        DB::table('admins')->insert([
            'first_name'=>'omar',
            'last_name'=>'omarain',
            'user_id'=>$user2->id
        ]);
    }
}
