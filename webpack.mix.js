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

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix.combine([
	'bower_components/jquery/dist/jquery.min.js',
	'bower_components/arrive/src/arrive.js',
	'bower_components/tether/dist/js/tether.min.js',
	'bower_components/jquery-ui/jquery-ui.min.js',
	'bower_components/moment/min/moment.min.js',
	'bower_components/bootstrap/dist/js/bootstrap.min.js',
	'bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js',
	'bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',
	'bower_components/validate/validate.min.js',
	'bower_components/chartist/dist/chartist.min.js',
	'bower_components/nouislider/distribute/nouislider.min.js',
	'bower_components/bootstrap-select/dist/js/bootstrap-select.min.js',
	'bower_components/datatables/media/js/jquery.dataTables.min.js',
	'bower_components/sweetalert2/dist/sweetalert2.min.js',
	'bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
	'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
	// Version from bower fucked shit up.. Reverting to the included one.
	'resources/assets/js/jquery.datatables.js',
	// Extracted spesific version from local. New from bower did not work well.
	'resources/assets/js/material.min.js',
	// Creative Tim has his modded version
	'resources/assets/js/bootstrap-notify.min.js'
], 'public/js/logit.min.js')

mix.combine(['resources/assets/js/material-dashboard.js'], 'public/js/material-dashboard.min.js').version();
mix.combine(['resources/assets/js/dashboard.js'], 'public/js/dashboard.min.js').version();
mix.combine(['resources/assets/js/friends.js'], 'public/js/friends.min.js').version();
mix.combine(['resources/assets/js/logitFuncs.js'], 'public/js/logitFuncs.min.js').version();
mix.combine(['resources/assets/js/myProfile.js'], 'public/js/myProfile.min.js').version();
mix.combine(['resources/assets/js/routines.js'], 'public/js/routines.min.js').version();
mix.combine(['resources/assets/js/settings.js'], 'public/js/settings.min.js').version();
mix.combine(['resources/assets/js/workouts.js'], 'public/js/workouts.min.js').version();
mix.combine(['resources/assets/js/timer.js'], 'public/js/timer.min.js').version();
mix.combine(['resources/assets/js/measurements.js'], 'public/js/measurements.min.js').version();