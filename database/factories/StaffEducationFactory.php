<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffInfoModel\StaffEducation::class, function (Faker $faker) {
    return [
        'school_name'   => $faker->userName,
        'subject'       => $faker->jobTitle,
        'start_date'    => $faker->date($format = 'Y-m-d', $max = '-10 years'),
        'end_date'      => $faker->date($format = 'Y-m-d', $max = 'now'),
        'degree_id'     => rand(1,6),
        'study_year'    => rand(1,8),
        'province_id'   => rand(1,25),
        'other_location'=> $faker->address,
        'noted'         => $faker->title,
        'created_by'    => 1,
    ];
});
