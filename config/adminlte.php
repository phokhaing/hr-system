<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'HRMS',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>HRMS</b>',

    'logo_mini' => '<b>HR</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => '',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'header' => 'MAIN MENU',
//            'can' => 'manage-blog'
        ],
        [
            'text' => 'Dashboard',
            'icon' => 'dashboard',
            'url' => '/dashboard'
        ],
        [
            'text' => 'Personnel Entry',
            'route' => 'staff-personal-info.index',
            'can' => 'view_staff',
            'icon' => 'user'
        ],
        [
            'text' => 'Contract Entry',
            'route' => 'contract.index',
            'can' => 'view_contract',
            'icon' => 'book'
        ],
        // [
        //     'text' => 'HR Training',
        //     'icon' => 'institution',
        //     'can' => 'view_hr_training',
        //     'submenu' => [
        //         [
        //             'text' => 'Orientation',
        //             'icon' => 'spinner',
        //             'url' => '/hrtraining/enrollment/'.CATEGORY_ORIENTATION.'/training',
        //             'can' => 'view_enrollment_schedule'
        //         ],
        //         [
        //             'text' => 'Refreshment',
        //             'icon' => 'recycle',
        //             'url' => '/hrtraining/enrollment/'.CATEGORY_REFRESHMENT.'/training',
        //             'can' => 'view_enrollment_schedule'
        //         ],
        //     ]
        // ],
        // [
        //     'text' => 'Staff Training',
        //     'icon' => 'institution',
        //     'can' => 'view_all_training_event',
        //     'submenu' => [
        //         [
        //             'text' => 'Orientation',
        //             'icon' => 'spinner',
        //             'url' => '/hrtraining/staff-enrollment/'.CATEGORY_ORIENTATION .'/staff-training',
        //             'can' => 'view_all_training_event'
        //         ],
        //         [
        //             'text' => 'Refreshment',
        //             'icon' => 'recycle',
        //             'url' => '/hrtraining/staff-enrollment/'.CATEGORY_REFRESHMENT.'/staff-training',
        //             'can' => 'view_all_training_event'
        //         ]
        //     ]
        // ],
        [
            'text' => 'Payroll',
            'icon' => 'dollar',
            'can' => 'view_payroll',
            'submenu' => [
                [
                    'text' => 'Payroll Half Month',
                    'route' => 'payroll.index',
                    'can' => 'view_half_payroll',
                    'icon' => 'hourglass-half'
                ],
                [
                    'text' => 'Payroll Full Month',
                    'url' => 'payroll/full-month',
                    'can' => 'view_full_payroll',
                    'icon' => 'hourglass'
                ],
            ]
        ],
        // [
        //     'text' => 'Final Pay',
        //     'url' => 'final-pay',
        //     'can' => 'block_contract_for_final_pay',
        //     'icon' => 'calculator'
        // ],
        // [
        //     'text' => 'Pension Fund',
        //     'icon' => 'shield',
        //     'can' => 'view_pension_fund',
        //     'submenu' => [
        //         [
        //             'text' => 'Migration Upload',
        //             'url' => 'pensionfund-migration/upload',
        //             'icon_color' => 'aqua',
        //             'icon' => 'upload',
        //             'can' => 'upload_pension_found_migration',
        //         ],
        //         [
        //             'text' => 'Upload',
        //             'url' => 'pensionfund/upload',
        //             'icon_color' => 'aqua',
        //             'icon' => 'upload',
        //             'can' => 'monthly_upload_pension_found',
        //         ],
        //         [
        //             'text' => 'Claim',
        //             'url' => 'pensionfund/claim',
        //             'icon_color' => 'yellow',
        //             'icon' => 'money',
        //             'can' => 'claim_pension_found',
        //         ]
        //     ],
        // ],
        [
            'header' => 'REPORTS',
            'can' => 'view_report'
        ],
        [
            'text' => 'Report',
            'can' => 'view_report',
            'icon' => 'line-chart',
            'url' => '/report/index',
        ],

        [
            'header' => 'USER AND ACCESS RIGHTS',
            'can' => 'view_access_right'
        ],
        [
            'text' => 'User And Access Rights',
            'icon' => 'shield',
            'can' => 'view_access_right',
            'submenu' => [
                [
                    'text' => 'User Entry',
                    'url' => 'users',
                    'icon_color' => 'aqua',
                    'icon' => 'user',
                    'can' => 'view_users',
                ],
                [
                    'text' => 'Role Entry',
                    'url' => 'roles',
                    'icon_color' => 'yellow',
                    'icon' => 'lock',
                    'can' => 'view_role',
                ],
                [
                    'text' => 'Permission Entry',
                    'url' => 'permissions',
                    'icon_color' => 'red',
                    'icon' => 'key',
                    'can' => 'view_permission',
                ],
            ],
        ],

        [
            'header' => 'SETTINGS',
            'can' => 'view_setting'
        ],
        [
            'text' => 'Setting',
            'icon' => 'cogs',
            'can' => 'view_setting',
            'submenu' => [
                [
                    'text' => 'Position Entry',
                    'url' => '/position',
                    'can' => 'view_position',
                    'icon' => 'briefcase'
                ],
                [
                    'text' => 'Branch & Department Entry',
                    'url' => '/branch-and-department',
                    'can' => 'view_branch_department',
                    'icon' => 'th-large'
                ],
                [
                    'text' => 'Company Entry',
                    'url' => '/company',
                    'can' => 'view_company',
                    'icon' => 'building'
                ],
                // [
                //     'text' => 'Category Entry',
                //     'url' => '/hrtraining/category-setting',
                //     'can' => 'view_category',
                //     'icon' => 'circle'
                // ],
                // [
                //     'text' => 'Course Entry',
                //     'url' => '/hrtraining/course-setting',
                //     'can' => 'view_course',
                //     'icon' => 'building'
                // ],
                // [
                //     'text' => 'Course Content Entry',
                //     'url' => '/hrtraining/course-content-setting',
                //     'can' => 'view_course_content',
                //     'icon' => 'building'
                // ],
                // [
                //     'text' => 'Examination Entry',
                //     'url' => '/hrtraining/examination-setting',
                //     'can' => 'view_exam',
                //     'icon' => 'file'
                // ],
                [
                    'text' => 'Payroll',
                    'route' => 'payroll.setting.index',
                    'can' => 'view_payroll_setting',
                    'icon' => 'dollar'
                ],
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => false,
        'select2' => true,
        'chartjs' => false,
    ],
];
