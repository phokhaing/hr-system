<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Initialize to role
     *
     * @return array
     */
    public static function defaultRoles()
    {
        return [

            'Administrator',
            'Manager',
            'Admin',
            'User',
        ];
    }

    /**
     * @return string[]
     */
    public static function trainingModule()
    {
        return [
          'Trainee',
          'Human Resource',
          'HR Manage All Training Company'
        ];
    }

}
