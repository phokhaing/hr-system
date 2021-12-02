<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('>> Do you wish to refresh migration before seeding, it will clear all old data ?')) {
            // disable fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Data cleared, starting from blank database.");

            // enable back fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Seed the default permissions
        $this->call(PermissionTableSeeder::class);

        // Confirm roles needed
        if ($this->command->confirm('>> Do you want create Roles for user, default is Administrator and user? [y|N]', true)) {

            // Seed the default permissions
            $this->call(RolesTableSeeder::class);

        } else {

            Role::firstOrCreate(['name' => 'User']);
            $this->command->info('Added only default user role.');
        }
    }
}
