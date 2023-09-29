<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $this->call([AdminRegistSeeder::class,]);
        $this->call([OwnerFirstAcc::class,]);
        $this->call([SubjectSeeder::class]);
        $this->call([ClassroomSeeder::class]);
        $this->call([ExamTypeSeeder::class]);
        $this->call([SchoolSeeder::class,]);
        $this->call([SchoolYearSeeder::class]);
        //$this->call([GeneralSeeder::class]);

    }
}
