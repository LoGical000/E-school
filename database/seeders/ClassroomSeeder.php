<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('classrooms')->insert([
    
            [
                'capacity' => 35,
                'room_number' => "7A",
                'grade_id'=>7,
            ],
            [
                'capacity' => 35,
                'room_number' => "8A",
                'grade_id'=>8,
            ],
            [
                'capacity' => 35,
                'room_number' => "9A",
                'grade_id'=>9,
            ],
            [
                'capacity' => 0,
                'room_number' => "none",
                'grade_id'=>7,
            ],
            [
                'capacity' => 0,
                'room_number' => "none",
                'grade_id'=>8,
            ],
            [
                'capacity' => 0,
                'room_number' => "none",
                'grade_id'=>9,
            ],


        ]);
    }

}
