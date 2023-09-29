<?php

namespace Database\Seeders;

use App\Models\Parentt;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GeneralSeeder extends Seeder
{

    public function run(): void
    {
        $faker = Faker::create();
        $parent_user=User::create([
            'email' =>'firstParent@schoolnaem.com',
            'password' => bcrypt('123456789'),
            'role'=>3
        ]);

        $parent=Parentt::query()->create([
            'father_first_name'=>$faker->firstNameMale,
            'father_last_name'=>$faker->lastName,
            'father_phone_number'=>$faker->phoneNumber,
            'mother_first_name'=>$faker->firstNameFemale,
            'mother_last_name'=>$faker->lastName,
            'mother_phone_number'=>$faker->phoneNumber,
            'national_id'=>$faker->randomNumber(9),
            'user_id'=>$parent_user->id,
        ]);

        for ($i = 1; $i <= 20; $i++) {
            $first_name=$faker->firstName();

            $student_user=User::create([
                'email' =>$first_name . $faker->randomNumber(4) . '@schoolname.com',
                'password' => bcrypt($faker->randomNumber(9)),
                'role'=>2
            ]);



            DB::table('students')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'religion' => $faker->randomElement(['Christian', 'Muslim', 'Hindu', 'Buddhist', 'Jewish']),
                'date_of_birth' => $faker->date,
                'address' => $faker->address,
                'details' => $faker->text,
                'grade_id' => $faker->numberBetween(7, 9),
                'gender_id' => $faker->numberBetween(1, 2),
                'parent_id' => $parent->parent_id,
                'user_id' => $student_user->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }

        for($i = 1; $i <= 15; $i++){
            $post = DB::table('posts')->insertGetId([
                'body' => $faker->paragraph,
                'date' => $faker->date,
                'user_id' => 1, // Assuming you have 10 users
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $likesCount = $faker->numberBetween(3, 10);
            for ($j = 1; $j <= $likesCount; $j++) {
                DB::table('likes')->insert([
                    'post_id' => $post,
                    'user_id' => $faker->numberBetween(1, 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


            $commentsCount = $faker->numberBetween(1, 7);
            for ($k = 1; $k <= $commentsCount; $k++) {
                DB::table('comments')->insert([
                    'post_id' => $post,
                    'user_id' => $faker->numberBetween(1, 10),
                    'body' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
