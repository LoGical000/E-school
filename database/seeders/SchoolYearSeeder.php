<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolYearSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('school_years')->insert([
            [
                'name' => "2022/2023",

            ],


        ]);
    }
}
