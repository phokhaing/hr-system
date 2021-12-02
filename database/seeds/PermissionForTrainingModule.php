<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionForTrainingModule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get permissions from training module
        $permissions = Permission::trainingModule();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
                'module_name' => 'training'
            ]);
        }

        $this->command->info('Training module permissions was added.');
    }
}
