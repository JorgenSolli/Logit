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

/* Dashboard */
Route::get('/dashboard', 'DashboardController@dashboard');
Route::get('/api/getSessions/{type}/{year}/{month}', 'DashboardController@getGrapData');
Route::get('/api/getAvgGymTime/{type}/{year}/{month}', 'DashboardController@getAvgGymTime');
Route::get('/api/getMusclegroups/{type}/{year}/{month}', 'DashboardController@getMusclegroups');

/* User/Settings */
Route::get('/user', 'UserController@myProfile');
Route::post('/user/edit', 'UserController@editProfile');
Route::get('/user/settings', 'SettingsController@settings');
Route::post('/user/settings/edit', 'SettingsController@editSettings');

/* Friends */
Route::get('dashboard/friends', 'FriendsController@viewFriends');
Route::get('dashboard/friends/findFriends', 'FriendsController@findFriends');
Route::get('dashboard/friends/sendRequest', 'FriendsController@sendRequest');
Route::get('dashboard/friends/respondRequest', 'FriendsController@respondRequest');
Route::get('api/friends/removeFriend', 'FriendsController@removeFriend');

/* Routines */
Route::get('/dashboard/my_routines', 'RoutineController@routines');
Route::get('/dashboard/my_routines/add_routine', 'RoutineController@addRoutine');

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
Route::get('/dashboard/measurements', 'MeasurementsController@measurements');

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
Route::get('showSession', 'DevController@showSession');