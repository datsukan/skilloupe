<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'user_id'           => 1,
        'title'             => $faker->realText(random_int(10, 50)),
        'started_at'        => date('Y-m-d'),
        'ended_at'          => date('Y-m-d'),
        'summary'           => $faker->realText(random_int(10, 500)),
        'member'            => random_int(1, 500),
        'system'            => [$faker->realText(10)],
        'region'            => [$faker->realText(10)],
        'role'              => [$faker->realText(10)],
        'dev_method'        => [$faker->realText(10)],
        'process'           => [$faker->realText(10)],
        'lang_and_fw'       => [$faker->realText(10)],
        'os_and_mw'         => [$faker->realText(10)],
        'tool'              => [$faker->realText(10)],
        'experience_detail' => $faker->realText(random_int(10, 1000)),
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s'),
    ];
});
