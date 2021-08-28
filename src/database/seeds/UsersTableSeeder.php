<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ja_JP');

        $insertParam = [];

        for ($i = 1; $i <= 20; $i++) {
            $insertParam[] = [
                'name'          => $faker->name,
                'email'         => "abcd+${i}@example.com",
                'password'      => bcrypt('password'),
                'is_readonly'   => random_int(0, 1),
                'is_manage'     => random_int(0, 1),
                'created_at'    => date('Y/m/d H:i:s'),
                'updated_at'    => date('Y/m/d H:i:s'),
            ];
        }

        DB::table('users')->insert($insertParam);
    }
}
