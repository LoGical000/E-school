<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerFirstAcc extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //
        $user=User::create([
            'email' => "OwnerOfSchool22@gmail.com",
            'password' => bcrypt('100Password1@2'),
            'role'=>0
        ]);

        DB::table('owners')->insert([
          'name'=>'owner name',
          'user_id'=> $user->id,
        ]);
    }

}
