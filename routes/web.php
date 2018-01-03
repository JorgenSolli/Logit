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

Auth::routes();

Route::get('/register/success', 'Auth\RegisterController@checkEmail');
Route::post('/register/resend', 'Auth\RegisterController@resend');
Route::get('/email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('/email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::group(['middleware' => ['isVerified']], function () {
	/* Dashboard */
	Route::get('/dashboard', 'DashboardController@dashboard');
	Route::get('/api/getSessions/{type}/{year}/{month}', 'DashboardController@getGrapData');
	Route::get('/api/getAvgGymTime/{type}/{year}/{month}', 'DashboardController@getAvgGymTime');
	Route::get('/api/getMusclegroups/{type}/{year}/{month}', 'DashboardController@getMusclegroups');
	Route::get('/api/getTopExercises/{type}/{year}/{month}', 'DashboardController@getTopExercises');
	Route::get('/api/getExerciseProgress/{type}/{year}/{month}/{exercise}', 'DashboardController@getExerciseProgress');

	/* User/Settings */
	Route::get('/user', 'UserController@myProfile');
	Route::post('/user/edit', 'UserController@editProfile');
	Route::get('/user/settings', 'SettingsController@settings');
	Route::post('/user/settings/edit', 'SettingsController@editSettings');
	Route::post('/user/settings/renameExercise', 'SettingsController@renameExercise');
	Route::get('/user/settings/get', 'SettingsController@getSettings');

	/* Friends */
	Route::get('/dashboard/friends', 'FriendsController@viewFriends');
	Route::get('/dashboard/friends/friend/{friendId}', 'FriendsController@viewFriend');
	Route::get('/dashboard/friends/findFriends', 'FriendsController@findFriends');
	Route::get('/dashboard/friends/sendRequest', 'FriendsController@sendRequest');
	Route::get('/dashboard/friends/respondRequest', 'FriendsController@respondRequest');
	Route::get('/api/friends/removeFriend', 'FriendsController@removeFriend');
	Route::post('/dashboard/friends/shareRoutine', 'FriendsController@shareRoutine');

	/* Routines */
	Route::get('/dashboard/my_routines', 'RoutineController@routines');
	Route::get('/dashboard/my_routines/add_routine', 'RoutineController@addRoutine');
	Route::get('/dashboard/my_routines/accept_routine/{routine}', 'RoutineController@acceptRoutine');
	Route::get('/api/routines/preview', 'RoutineController@previewRoutine');

	// Create
	Route::put('/dashboard/my_routines', 'RoutineController@insertRoutine');
	// Read
	Route::get('/dashboard/my_routines/view/{routine}', 'RoutineController@viewRoutine');
	// Update
	Route::post('/dashboard/my_routines/edit/{routine}', 'RoutineController@updateRoutine');
	// Status
	Route::post('/dashboard/my_routines/edit/status/{routine}', 'RoutineController@changeStatus');
	// Delete
	Route::get('/dashboard/my_routines/delete/{routine}', 'RoutineController@deleteRoutine');

	/* Settings */
	Route::get('/dashboard/settings', 'SettingsController@viewSettings');

	/* Measurements */
	Route::get('/dashboard/measurements', 'MeasurementController@measurements');
	Route::post('/dashboard/measurements/save', 'MeasurementController@saveMeasurements');
	Route::post('/dashboard/measurements/delete', 'MeasurementController@deleteMeasurement');

	/* Workouts */
	Route::get('/dashboard/workouts', 'WorkoutController@viewWorkouts');
	Route::get('/api/get_workout/view/{workoutId}', 'WorkoutController@getWorkout');
	Route::get('/api/delete_workout/{workout}', 'WorkoutController@deleteWorkout');
	Route::get('/api/update_workout/{workout}', 'WorkoutController@updateWorkout');
	Route::get('/dashboard/start', 'WorkoutController@selectWorkout');
	Route::get('/dashboard/start/{routine}', 'WorkoutController@startWorkout');
	Route::get('/dashboard/workout/finish/{routine_id}', 'WorkoutController@finishWorkout');
	Route::get('/dashboard/workout/recap/{workout}', 'WorkoutController@recap');

	/* Exercises */
	Route::get('/api/exercise/{exerciseId}', 'ExerciseController@getExercise');
	Route::put('/api/exercise/{routineId}', 'ExerciseController@addExercise');

	/* API routes */
	Route::get('/clear', 'ApiController@flushSessions');
	Route::post('/api/notifications/check', 'ApiController@checkNotifications');
	Route::post('/api/notifications/clear/', 'ApiController@clearNotification');

	/* Dev paths */
	Route::get('/showSession', 'DevController@showSession');
});