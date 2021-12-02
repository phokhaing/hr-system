<?php

use App\Contract;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ContractSeeder extends Seeder
{
    /**
     * @param Faker $faker
     */
    public function run(Faker $faker)
    {
        for ($i = 1; $i <= 50; $i++) {
        $company = DB::table('companies')->pluck('company_code');
        $branch = DB::table('branches_and_departments')->pluck('code');
        $position = DB::table('positions')->pluck('code');

        $data = [
            "staff_code" => rand(0100,2000),
            "staff_personal_info_id" => rand(550,2000),
            "company_profile" => array_random($company->toArray()).array_random($branch->toArray()).array_random($position->toArray()),
            "contract_object" => [
                "company" => [
                    "id" => rand(1,9999),
                    "code" => array_random($company->toArray()),
                    "short_name" => $faker->companySuffix,
                    "name_en" => $faker->company,
                    "name_kh" => $faker->company
                ],
                "branch_department" => [
                    "id" => rand(1,9999),
                    "code" => array_random($branch->toArray()),
                    "short_name" => $faker->streetName,
                    "name_en" => $faker->locale,
                    "name_kh" => $faker->locale
                ],
                "position" => [
                    "id" => rand(1,9999),
                    "code" => array_random($position->toArray()),
                    "short_name" => $faker->name,
                    "name_en" => $faker->jobTitle,
                    "name_kh" => $faker->jobTitle
                ],
                "salary" => rand(1000,10000),
                "currency" => array_random(array("USD", "KHR")),
                "probation_end_date" => $faker->dateTime,
                "contract_last_date" => $faker->dateTime, // save this field for check as current contract staff
                "phone_number" => $faker->phoneNumber,
                "email" => $faker->safeEmail,
                "manager" => null
            ],
            "created_by" => $faker->userName,
            "start_date" => $faker->dateTime,
            "end_date" => $faker->dateTime,
            "contract_type" => rand(1,9)
        ];

        $contract = new Contract();
        $contract->createRecord($data);
    }
    }
}
