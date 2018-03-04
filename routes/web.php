<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about/privacy_policy', function() {
	return view('about.privacyPolicy');
});
Route::get('/about/tos', function() {
	return view('about.tos');
});

Auth::routes();

// OAuth Routes
Route::prefix('auth/{provider}')->group(function() {
	Route::get('', 'Auth\AuthController@redirectToProvider');
	Route::get('callback', 'Auth\AuthController@handleProviderCallback');
});

Route::get('/register/success', 'Auth\RegisterController@checkEmail');
Route::post('/register/resend', 'Auth\RegisterController@resend');
Route::get('/email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('/email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::group(['middleware' => ['isVerified']], function () {
	/* Dashboard */
	Route::prefix('dashboard')->group(function() {
		Route::get('', 'DashboardController@index')->name('dashboard');
		Route::post('getTotalWorkouts/{type}/{year}/{month}', 'DashboardController@getSessionData');
		Route::post('getAvgGymTime/{type}/{year}/{month}', 'DashboardController@getAvgGymTime');
		Route::post('getMusclegroups/{type}/{year}/{month}', 'DashboardController@getMusclegroups');
		Route::post('getTopExercises/{type}/{year}/{month}', 'DashboardController@getTopExercises');
		Route::post('getExerciseProgress/{type}/{year}/{month}/{exercise}', 'DashboardController@getExerciseProgress');
		Route::post('getCompletionRatio/{type}/{year}/{month}', 'DashboardController@getCompletionRatio');
	});

	/* User/Settings */
	Route::prefix('user')->group(function() {
		Route::get('', 'UserController@myProfile')->name('user');
		Route::post('edit', 'UserController@editProfile');
		Route::get('settings', 'SettingsController@index')->name('settings');
		Route::post('settings/edit', 'SettingsController@editSettings');
		Route::post('settings/edit/timer', 'SettingsController@timerSettings');
		Route::post('settings/renameExercise', 'SettingsController@renameExercise');
		Route::get('settings/get', 'SettingsController@getSettings');
	});

	/* Friends */
	Route::prefix('friends')->group(function() {
		Route::get('', 'FriendsController@viewFriends')->name('friends');
		Route::get('findFriends', 'FriendsController@findFriends');
		Route::get('sendRequest', 'FriendsController@sendRequest');
		Route::get('respondRequest', 'FriendsController@respondRequest');

		/* Friend */
		Route::prefix('{friendId}')->group(function() {
			Route::get('', 'FriendController@viewFriend');
			Route::get('remove', 'FriendController@removeFriend');
			Route::get('populateExercises', 'FriendController@getExercises');
			Route::get('getExerciseData', 'FriendController@getExerciseData');
			Route::get('/getSessionData', 'FriendController@getSessionData');
			Route::post('shareRoutine', 'FriendController@shareRoutine');
		});
	});

	/* Routines */
	Route::prefix('routines')->group(function() {
		Route::get('', 'RoutineController@index')->name('myRoutines');
		Route::put('', 'RoutineController@createRoutine');
		Route::get('new_routine', 'RoutineController@newRoutine');
		Route::get('accept_routine/{routine}', 'RoutineController@acceptRoutine');
		Route::get('preview', 'RoutineController@previewRoutine');

		/* Routine */
		Route::prefix('{routine}')->group(function() {
			Route::get('', 'RoutineController@viewRoutine');
			Route::post('edit', 'RoutineController@updateRoutine');
			Route::get('delete', 'RoutineController@deleteRoutine');
			Route::post('edit/status', 'RoutineController@changeStatus');
		});
	});

	/* Measurements */
	Route::prefix('measurements')->group(function() {
		Route::get('', 'MeasurementController@index')->name('measurements');
		Route::post('save', 'MeasurementController@create');
		Route::post('get_measurements', 'MeasurementController@read');
		Route::post('delete', 'MeasurementController@delete');
	});

	/* Workout session*/
	Route::prefix('start_workout')->group(function() {
		Route::get('', 'StartWorkoutController@index')->name('startWorkout');
		Route::get('{routine}', 'StartWorkoutController@read');
		Route::get('{routine_id}/finish', 'StartWorkoutController@create');
		Route::get('/session/clear', 'StartWorkoutController@clearSession');
	});

	/* Workouts */
	Route::prefix('workouts')->group(function() {
		Route::get('', 'WorkoutController@index')->name('workouts');
		Route::prefix('{workout}')->group(function() {
			Route::get('', 'WorkoutController@read');
			Route::delete('delete', 'WorkoutController@deleteWorkout');
			Route::patch('update', 'WorkoutController@update');
			Route::get('recap', 'WorkoutController@recap');
		});
	});

	/* Exercises */
	Route::prefix('exercises')->group(function() {
		Route::get('{exerciseId}', 'ExerciseController@getExercise');
		Route::put('{routineId}/{exerciseId}', 'ExerciseController@addExercise');
	});

	/* Social */
	Route::prefix('social')->group(function() {
		Route::post('notifications/check', 'SocialController@checkNotifications');
		Route::post('notifications/clear', 'SocialController@clearNotification');
		Route::get('message/clear', 'SocialController@clearMessage');
	});

	/* Dev/Admin paths */
	Route::get('/admin/showSession', 'DevController@showSession');
	Route::get('/admin', 'DevController@adminPanel');
	Route::post('/admin/newMessage', 'DevController@newMessage');
});