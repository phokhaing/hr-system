<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends \Spatie\Permission\Models\Permission
{
    use SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [
        'id', 'name', 'created_by', 'updated_by', 'guard_name', 'module_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_role',
            'add_role',
            'edit_role',
            'delete_role',

            'view_permission',
            'add_permission',
            'edit_permission',
            'delete_permission',

            'view_report',
            'add_report',
            'edit_report',
            'delete_report',

            'view_setting',
            'add_setting',
            'edit_setting',
            'delete_setting',

            'view_staff',
            'add_staff',
            'edit_staff',
            'delete_staff',

            'view_staff_movement',
            'add_staff_movement',
            'edit_staff_movement',
            'delete_staff_movement',

            'view_resign',
            'add_resign',
            'edit_resign',
            'delete_resign',

            'view_access_right',

            'view_position',
            'add_position',
            'edit_position',
            'delete_position',

            'view_branch_department',
            'add_branch_department',
            'edit_branch_department',
            'delete_branch_department',

            'view_company',
            'add_company',
            'edit_company',
            'delete_company',

            'view_contract',
            'add_contract',
            'edit_contract',
            'delete_contract',

            'view_hr_training',
            'add_hr_training',
            'edit_hr_training',
            'delete_hr_training',

            'view_all_training_event',
            'add_all_training_event',
            'edit_all_training_event',
            'delete_all_training_event',

            'view_my_training_schedule',
            'add_my_training_schedule',
            'edit_my_training_schedule',
            'delete_my_training_schedule',

            'view_my_training_history',
            'add_my_training_history',
            'edit_my_training_history',
            'delete_my_training_history',

            'view_enrollment_schedule',
            'add_enrollment_schedule',
            'edit_enrollment_schedule',
            'delete_enrollment_schedule',

            'view_category',
            'add_category',
            'edit_category',
            'delete_category',

            'view_course',
            'add_course',
            'edit_course',
            'delete_course',

            'view_course_content',
            'add_course_content',
            'edit_course_content',
            'delete_course_content',

            'view_exam', // For HR (review exam)
            'add_exam',
            'edit_exam',
            'delete_exam',

            'view_my_result_exam',
            'add_my_result_exam',
            'edit_my_result_exam',
            'delete_my_result_exam',
        ];
    }

    /**
     * => NOTED: All permission that return in this function
     *    must be present in defaultPermissions()
     *
     * @return string[]
     */
    public static function trainingModule()
    {
        return [
            'view_hr_training',
            'add_hr_training',
            'edit_hr_training',
            'delete_hr_training',

            'view_all_training_event',
            'add_all_training_event',
            'edit_all_training_event',
            'delete_all_training_event',

            'view_my_training_schedule',
            'add_my_training_schedule',
            'edit_my_training_schedule',
            'delete_my_training_schedule',

            'view_my_training_history',
            'add_my_training_history',
            'edit_my_training_history',
            'delete_my_training_history',

            'view_enrollment_schedule',
            'add_enrollment_schedule',
            'edit_enrollment_schedule',
            'delete_enrollment_schedule',

            'view_category',
            'add_category',
            'edit_category',
            'delete_category',

            'view_course',
            'add_course',
            'edit_course',
            'delete_course',

            'view_course_content',
            'add_course_content',
            'edit_course_content',
            'delete_course_content',

            'view_exam',
            'add_exam',
            'edit_exam',
            'delete_exam',

            'view_my_result_exam',
            'add_my_result_exam',
            'edit_my_result_exam',
            'delete_my_result_exam',

            '	manage_all_training_company'
        ];
    }

    /**
     * @return string[]
     */
    public static function payrollModule()
    {
        return [
            'view_payroll',

            'view_half_payroll',
            'checking_half_payroll',
            'post_half_payroll',
            'download_half_payroll',
            'view_report_half_payroll',
            'download_report_half_payroll',

            'view_full_payroll',
            'checking_full_payroll',
            'post_full_payroll',
            'download_full_payroll',
            'view_report_full_payroll',
            'download_report_full_payroll',

        ];
    }
}
