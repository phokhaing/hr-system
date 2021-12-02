<?php

use Faker\Generator as Faker;

$factory->define(App\StaffInfoModel\StaffTraining::class, function (Faker $faker) {
    return [
        'subject'       => $faker->jobTitle,
        'school'        => $faker->jobTitle,
        'place'         => $faker->address,
        'start_date'    => $faker->date($format = 'Y-m-d', $mix = 'now'),
        'end_date'      => $faker->date($format = 'Y-m-d', $mix = 'now'),
        'province_id'   => rand(1,25),
        'other_location'=> $faker->address,
        'description'   => $faker->title,
        'created_by'    => 1,
    ];
});
