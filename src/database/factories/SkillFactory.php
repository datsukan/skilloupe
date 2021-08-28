<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Skill;
use Faker\Generator as Faker;

$factory->define(Skill::class, function (Faker $faker) {
    return [
        'user_id'           => 1,
        'name'              => $faker->realText(random_int(10, 50)),
        'type'              => $faker->realText(random_int(10, 50)),
        'level'             => random_int(0, 5),
        'experience_period' => random_int(0, 100),
        'tool'              => [$faker->realText(10)],
        'platform'          => [$faker->realText(10)],
        'system'            => [$faker->realText(10)],
        'experience_detail' => $faker->realText(random_int(10, 1000)),
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s'),
    ];
});
