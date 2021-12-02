<?php

use Faker\Generator as Faker;

$factory->define(App\StaffInfoModel\StaffPersonalInfo::class, function (Faker $faker) {
    $phoneNumber = "099".rand(123456, 999999);
    return [
        'first_name_en'  => $faker->firstName,
        'last_name_en'  => $faker->lastName,
        'first_name_kh'  => $faker->firstName,
        'last_name_kh'  => $faker->lastName,
        'gender'    => rand(0,1),
        'marital_status' => rand(1,2),
        'id_type'   => rand(1,5),
        'id_code'   => $faker->creditCardNumber,
        'dob'       => $faker->date($format = 'Y-m-d', $min = '-18 years'),
        'pob'       => $faker->address,
        'bank_name' => rand(1,3),
        'bank_acc_no' => $faker->bankAccountNumber,
        'driver_license' => $faker->title,
        'height'       => rand(150, 220),
        'house_no'  => rand(1, 500),
        'street_no' => rand(1, 500),
        'other_location'     => $faker->address,
        'phone'        => $phoneNumber,
        'email'        => $faker->safeEmail,
        'emergency_contact' => $phoneNumber,
        'noted'         => $faker->text,
        'created_by'    => 1,
    ];
});
