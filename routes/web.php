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

// Routines
Route::get('/dashboard/my_routines', 'RoutineController@routines');
Route::get('/dashboard/my_routines/add_routine', 'RoutineController@addRoutine');
Route::get('/dashboard/my_routines/{routine}', 'RoutineController@viewRoutine');

Route::post('/dashboard/my_routines/add_routine', 'DashboardController@insertRoutine');