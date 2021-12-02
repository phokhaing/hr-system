<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StaffPersonalInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\StaffInfoModel\StaffPersonalInfo::class, 20)->create()->each(function ($personal) {
            $personal->profile()->save(factory(\App\StaffInfoModel\StaffProfile::class)->make());
            $personal->educations()->save(factory(\App\StaffInfoModel\StaffEducation::class)->make());
            $personal->trainings()->save(factory(\App\StaffInfoModel\StaffTraining::class)->make());
            $personal->experiences()->save(factory(\App\StaffInfoModel\StaffExperience::class)->make());
            $personal->spouse()->save(factory(\App\StaffInfoModel\StaffSpouse::class)->make());
            $personal->guarantors()->save(factory(\App\StaffInfoModel\StaffGuarantor::class)->make());
        });

        $this->command->info('Your seeder was run!');
    }
}
