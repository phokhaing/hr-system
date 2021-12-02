let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
    'resources/assets/js/app.js',
    'resources/assets/js/themes/jquery-ui.js'
    ], 'public/js/app.js')
    .js('resources/assets/js/unity.js', 'public/js/unity.js')
    .js('resources/assets/js/staff/index.js', 'public/js/staff/index.js')
    .js('resources/assets/js/staff_movement/index.js', 'public/js/staff_movement/index.js')
    .js('resources/assets/js/staff_movement/create.js', 'public/js/staff_movement/create.js')
    .js('resources/assets/js/staff_resign/index.js', 'public/js/staff_resign/index.js')
    .js('resources/assets/js/staff_resign/create.js', 'public/js/staff_resign/create.js')
    .js('resources/assets/js/reports/staff_profile/index.js', 'public/js/reports/staff_profile/index.js')

    .sass('resources/assets/sass/app.scss', 'public/css')

    .styles('public/vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.css')
    .styles('public/vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css', 'public/css/font-awesome.css')
    .styles('public/vendor/adminlte/vendor/Ionicons/css/ionicons.min.css', 'public/css/ionicons.css')
    .styles('public/vendor/adminlte/dist/css/AdminLTE.min.css', 'public/css/AdminLTE.css')
    .styles('public/sweet_alert2/sweetalert2.min.css', 'public/css/sweetalert2.css');