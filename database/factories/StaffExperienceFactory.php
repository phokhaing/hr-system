<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffInfoModel\StaffExperience::class, function (Faker $faker) {
    return [
        'position_id'       => rand(1,20),
        'level_id'          => rand(1,7),
        'start_date'        => $faker->date($format = 'Y-m-d', $mix = 'now'),
        'end_date'          => $faker->date($format = 'Y-m-d', $mix = 'now'),
        'company_name_en'   => $faker->company,
        'company_name_kh'   => $faker->company,
        'province_id'       => rand(1,25),
        'house_no'          => rand(10, 9999),
        'street_no'         => rand(10, 7890),
        'other_location'    => $faker->address,
        'noted'             => $faker->title,
        'created_by'        => 1
    ];
});
