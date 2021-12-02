<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffInfoModel\StaffGuarantor::class, function (Faker $faker) {
    return [
        'first_name_kh' => $faker->firstName,
        'last_name_kh'  => $faker->lastName,
        'first_name_en' => $faker->firstName,
        'last_name_en'  => $faker->lastName,
        'gender'    => rand(0,1),
        'dob'       => $faker->date($format = 'Y-m-d', $min = 'now'),
        'pob'       => $faker->address,
        'id_type'   => rand(1,6),
        'id_code'   => $faker->creditCardNumber,
        'career_id' => rand(1,15),
        'marital_status'=> rand(1,2),
        'related_id'    => rand(1,6),
        'province_id'   => rand(1,25),
        'house_no'      => rand(123,123456),
        'street_no'     => rand(12,56789),
        'other_location'=> $faker->address,
        'email'     => $faker->safeEmail,
        'phone'     => $faker->phoneNumber,
        'created_by'=> 1,
    ];
});
