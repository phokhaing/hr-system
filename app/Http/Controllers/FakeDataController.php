<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Generator as Faker;

class FakeDataController extends Controller
{
    public function fakeData(Faker $faker)
    {
        $phoneNumber = "099".rand(123456, 999999);

        $data = [
            'fistName'  => $faker->firstName,
            'lastName'  => $faker->lastName,
            'gender'    => rand(0,1),
            'id_type'   => rand(1,5),
            'id_number' => $faker->creditCardNumber,
            'dob'       => $faker->date('d-M-Y'),
            'pob'       => $faker->address,
            'bank_name' => rand(1,3),
            'bank_acc_number' => $faker->bankAccountNumber,
            'driver_licence' => $faker->title,
            '_height'       => rand(150, 220),
            'house_number'  => rand(1, 500),
            'street_number' => rand(1, 500),
            '_location'     => $faker->address,
            '_phone'        => $phoneNumber,
            '_email'        => $faker->safeEmail,
            'emergency_contact' => $phoneNumber,
            'noted'         => $faker->text
        ];

        return \response()->json($data);
    }
}
