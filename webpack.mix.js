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
	'node_modules/jquery/dist/jquery.min.js',
	'node_modules/arrive/src/arrive.js',
	'node_modules/tether/dist/js/tether.min.js',
	'node_modules/jquery-ui-dist/jquery-ui.min.js',
	'node_modules/moment/min/moment.min.js',
	'node_modules/chart.js/dist/Chart.min.js',
	'node_modules/bootstrap/dist/js/bootstrap.min.js',
	'node_modules/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',
	'node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js',
	'node_modules/validate.js/validate.min.js',
	'node_modules/nouislider/distribute/nouislider.min.js',
	'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
	'node_modules/datatables/media/js/jquery.dataTables.min.js',
	'node_modules/sweetalert2/dist/sweetalert2.min.js',
	'node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
	'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
	'node_modules/chartist/dist/chartist.min.js',
	'node_modules/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js',
	'node_modules/chartist-plugin-axistitle/dist/chartist-plugin-axistitle.min.js',
	'node_modules/chartist-plugin-threshold/dist/chartist-plugin-threshold.js',
	'node_modules/easytimer.js/dist/easytimer.js',
	'node_modules/intro.js/minified/intro.min.js',
	'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
	// Version from bower fucked shit up.. Reverting to the included one.
	'resources/assets/js/jquery.datatables.js',
	// Extracted spesific version from local. New from bower did not work well.
	'resources/assets/js/material.min.js',
	// Creative Tim has his modded version
	'resources/assets/js/bootstrap-notify.min.js',
], 'public/js/logit.min.js')

mix.combine(['resources/assets/js/material-dashboard.js'], 'public/js/material-dashboard.min.js').version();
mix.combine(['resources/assets/js/dashboard.js'], 'public/js/dashboard.min.js').version();
mix.combine(['resources/assets/js/friends.js'], 'public/js/friends.min.js').version();
mix.combine(['resources/assets/js/friend.js'], 'public/js/friend.min.js').version();
mix.combine(['resources/assets/js/logitFuncs.js'], 'public/js/logitFuncs.min.js').version();
mix.combine(['resources/assets/js/myProfile.js'], 'public/js/myProfile.min.js').version();
mix.combine(['resources/assets/js/routines.js'], 'public/js/routines.min.js').version();
mix.combine(['resources/assets/js/settings.js'], 'public/js/settings.min.js').version();
mix.babel(['resources/assets/js/workouts.js'], 'public/js/workouts.min.js').version();
mix.combine(['resources/assets/js/startWorkout.js'], 'public/js/startWorkout.min.js').version();
mix.combine(['resources/assets/js/timer.js'], 'public/js/timer.min.js').version();
mix.combine(['resources/assets/js/measurements.js'], 'public/js/measurements.min.js').version();
mix.combine(['resources/assets/js/admin.js'], 'public/js/admin.min.js').version();