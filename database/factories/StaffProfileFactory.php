<?php

use Faker\Generator as Faker;

$factory->define(App\StaffInfoModel\StaffProfile::class, function (Faker $faker) {
    return [
//        'staff_personal_info_id',
        'emp_id_card' => rand(11111, 999999),
        'probation_duration' => rand(1,6),
        'contract_duration' => rand(12, 24),
        'branch_id' => rand(1, 2),
        'company_id' => rand(1, 2),
        'dpt_id' => rand(1, 2),
        'position_id' => rand(1, 2),
        'base_salary' => rand(500, 3000),
        'currency' => rand(1,2),
        'employment_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'probation_end_date' => $faker->date($format = 'Y-m-d', $min = '+5 months'),
        'contract_end_date' => $faker->date($format = 'Y-m-d', $min = '+2 years'),
        'manager' => $faker->name,
        'email' => $faker->email,
        'phone' =>  $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'created_by' => 1,
    ];
});
