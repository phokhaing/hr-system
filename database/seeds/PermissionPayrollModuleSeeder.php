<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionPayrollModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get permissions from training module
        $permissions = Permission::payrollModule();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
                'module_name' => 'payroll'
            ]);
        }

        $this->command->info('Payroll module permissions was added.');
    }
}
