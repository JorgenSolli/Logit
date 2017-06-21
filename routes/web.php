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

Route::get('/dashboard', 'DashboardController@dashboard');
Route::get('/clear', 'ApiController@flushSessions');

/* User */
Route::get('/user', 'UserController@myProfile');
Route::post('/user/edit', 'UserController@editProfile');
Route::get('/user/settings', 'UserController@settings');

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

/* Workouts */
Route::get('/dashboard/workouts', 'WorkoutController@viewWorkouts');
Route::get('/dashboard/start', 'WorkoutController@selectWorkout');
Route::get('/dashboard/start/{routine}', 'WorkoutController@startWorkout');
Route::get('/dashboard/workout/finish/{routine_id}', 'WorkoutController@finishWorkout');
// Read

/* APIs */
Route::get('/api/exercise/{exerciseId}', 'ApiController@getExercise');
Route::put('/api/exercise/{routineId}', 'ApiController@addExercise');
Route::get('/api/getSessions/{type}/{year}/{month}', 'ApiController@getGrapData');
Route::get('/api/getAvgGymTime/{type}/{year}/{month}', 'ApiController@getAvgGymTime');
Route::get('/api/get_workout/view/{workoutId}', 'ApiController@getWorkout');
Route::get('/api/delete_workout/{workout}', 'ApiController@deleteWorkout');
Route::get('/api/update_workout/{workout}', 'ApiController@updateWorkout');