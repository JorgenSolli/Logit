const { mix } = require('laravel-mix');

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

mix.sass('resources/assets/sass/app.scss', 'public/css').sourceMaps();

mix.combine([
	'resources/assets/js/material-dashboard/core/jquery.min.js',
	'resources/assets/js/material-dashboard/core/popper.min.js',
	'resources/assets/js/material-dashboard/bootstrap-material-design.min.js',
	'resources/assets/js/material-dashboard/plugins/moment.min.js',
	'resources/assets/js/material-dashboard/plugins/bootstrap-selectpicker.js',
	'resources/assets/js/material-dashboard/plugins/bootstrap-tagsinput.js',
	//'resources/assets/js/material-dashboard/plugins/bootstrap.min.js',
	'resources/assets/js/material-dashboard/plugins/jasny-bootstrap.min.js',
	'resources/assets/js/material-dashboard/plugins/arrive.min.js',
	'resources/assets/js/material-dashboard/plugins/jquery.validate.min.js',
	'resources/assets/js/material-dashboard/plugins/bootstrap-notify.js',
	'resources/assets/js/material-dashboard/plugins/nouislider.min.js',
	'resources/assets/js/material-dashboard/plugins/jquery.select-bootstrap.js',
	'resources/assets/js/material-dashboard/plugins/jquery.datatables.js',
	'resources/assets/js/material-dashboard/plugins/sweetalert2.js',
	'resources/assets/js/material-dashboard/plugins/bootstrap-datetimepicker.min.js',
	'resources/assets/js/material-dashboard/plugins/jquery.tagsinput.js',
	'resources/assets/js/material-dashboard/plugins/perfect-scrollbar.jquery.min.js',

	'node_modules/chart.js/dist/Chart.min.js',
	'node_modules/easytimer.js/dist/easytimer.js',
	'node_modules/jquery-ui-dist/jquery-ui.min.js',

	'resources/assets/js/material-dashboard/material-dashboard.js',
	'resources/assets/js/material-dashboard/functions.js',
], 'public/js/logit.min.js')

mix.combine(['resources/assets/js/dashboard.js'], 'public/js/dashboard.min.js').version();
mix.combine(['resources/assets/js/friends.js'], 'public/js/friends.min.js').version();
mix.combine(['resources/assets/js/friend.js'], 'public/js/friend.min.js').version();
mix.combine(['resources/assets/js/logitFuncs.js'], 'public/js/logitFuncs.min.js').version();
mix.combine(['resources/assets/js/myProfile.js'], 'public/js/myProfile.min.js').version();
mix.combine(['resources/assets/js/routines.js'], 'public/js/routines.min.js').version();
mix.combine(['resources/assets/js/settings.js'], 'public/js/settings.min.js').version();
mix.combine(['resources/assets/js/timer.js'], 'public/js/timer.min.js').version();
mix.combine(['resources/assets/js/measurements.js'], 'public/js/measurements.min.js').version();
mix.combine(['resources/assets/js/admin.js'], 'public/js/admin.min.js').version();
mix.babel(['resources/assets/js/workouts.js'], 'public/js/workouts.min.js').version();
mix.babel(['resources/assets/js/startWorkout.js'], 'public/js/startWorkout.min.js').version();