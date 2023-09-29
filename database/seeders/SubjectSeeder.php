<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('subjects')->insert([
            [
                'name' => "none",
                'max_mark'=>0
            ],
            [
                'name' => "Physics",
                'max_mark'=>100
            ],
            [
                'name' => "Math",
                'max_mark'=>100
            ],
            [
                'name' => "English",
                'max_mark'=>100
            ],
            [
                'name' => "Science",
                'max_mark'=>100
            ]

        ]);
    }
}
