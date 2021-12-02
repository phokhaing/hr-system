<?php

use Illuminate\Database\Seeder;
use App\Role as Roles;
use App\Permission;
use App\User;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get default roles form initialize
        $roles = Roles::defaultRoles();

        foreach ($roles as $role) {

            $role = Role::Create(['name' => $role, 'guard_name' => 'web']);

            if ($role->name == 'Administrator') {
                // assign all permissions
                $role->syncPermissions(Permission::all()->pluck('id'));
                $this->command->info('Administrator granted all the permissions');

            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->pluck('id'));
            }

            // create one user for each role
            $this->createUser($role);
        }

        $this->command->info('Roles ' .implode( ", ", $roles ). ' added successfully');
    }

    /**
     * Create user and assign role to user
     *
     * @param $role
     */
    private function createUser($role) {

        $user = factory(User::class)->create();
        $user->assignRole($role->name);

        if( $role->name == 'Administrator' ) {
            $this->command->info('Here is your administrator details to login:');
            $this->command->warn('Username is"'.$user->name.'"');
            $this->command->warn('Password is "secret"');
        }
    }
}
