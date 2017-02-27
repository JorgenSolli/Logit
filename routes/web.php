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

	if (Auth::guest()) {
    	return redirect('/login');
	} else {
		return redirect('/dashboard');
	}
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@dashboard');
Route::get('/clear', 'ApiController@flushSessions');

/* Routines */
Route::get('/dashboard/my_routines', 'RoutineController@routines');
Route::get('/dashboard/my_routines/add_routine', 'RoutineController@addRoutine');

// Create
Route::put('/dashboard/my_routines', 'RoutineController@insertRoutine');
// Read
Route::get('/dashboard/my_routines/view/{routine}', 'RoutineController@viewRoutine');
// Update
Route::post('/dashboard/my_routines/edit/{routine}', 'RoutineController@updateRoutine');
// Delete
Route::delete('/dashboard/my_routines/{routine}', 'RoutineController@deleteRoutine');

/* Workouts */
Route::get('/dashboard/workouts', 'WorkoutController@viewWorkouts');
Route::get('/dashboard/start', 'WorkoutController@selectWorkout');
Route::get('/dashboard/start/{routine}', 'WorkoutController@startWorkout');
Route::get('/dashboard/workout/finish/{routine_id}', 'WorkoutController@finishWorkout');

/* Exercises */
Route::get('/api/exercise/{exerciseId}', 'ApiController@getExercise');
Route::put('/api/exercise/{routineId}', 'ApiController@addExercise');
Route::get('/api/getSessions/{type}/{year}/{month}', 'ApiController@getGrapData');