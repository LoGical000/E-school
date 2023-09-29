<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypeSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('exams_type')->insert([
            [
                'name' => "مذاكرة اولى",
            ],
            [
                'name' => "فحص نصفي",
            ],
            [
                'name' => "مذاكرة ثانية",
            ],
            [
                'name'=>'فحص نهائي'
            ]

        ]);
    }
}
