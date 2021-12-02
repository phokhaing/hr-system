<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffInfoModel\StaffSpouse::class, function (Faker $faker) {
    return [
        "full_name" => $faker->name,
        "gender" => rand(0,1),
        "children_no" => rand(5, 10),
        "province_id" => rand(1,25),
        "house_no" => rand(2, 2678),
        "street_no" => rand(1,5678),
        "other_location" => $faker->address,
        "phone" => $faker->phoneNumber,
        "spouse_tax" => rand(1, 2),
        "children_tax" => rand(0, 5),
        "occupation_id" => rand(1, 15),
        "dob" => $faker->date($format = 'Y-m-d', $max = 'now'),
        "created_by"  => 1
    ];
});
