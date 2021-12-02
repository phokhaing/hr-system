<?php

use Illuminate\Database\Seeder;
use App\Role as Roles;
use App\Permission;
use Spatie\Permission\Models\Role;

class RolseForTrainingModule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get default roles form initialize
        $roles = Roles::trainingModule();

        foreach ($roles as $role) {

            $role = Role::updateOrCreate(['name' => $role, 'guard_name' => 'web']);

            if ($role->name == 'Human Resource') {
                // assign all permissions
                $role->syncPermissions(
                    Permission::where('module_name', 'training')
                        ->pluck('id')
                );
                $this->command->info('Trainer granted all the permissions for Training Module.');

            } else {
                // for others by default only read access
                $role->syncPermissions(
                    Permission::where('module_name', 'training')
                        ->where('name', 'LIKE', 'view_%')
                        ->pluck('id')
                );
            }

            // create one user for each role
            // $this->createUser($role);
        }

        $this->command->info('Roles ' .implode( ", ", $roles ). ' added successfully');
    }
}
