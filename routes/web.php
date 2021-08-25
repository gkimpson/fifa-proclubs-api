<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'StatsController@index')->name('stats.index');
Route::get('/clubsinfo', 'App\Http\Controllers\StatsController@clubsInfo');
Route::get('/careerstats', 'App\Http\Controllers\StatsController@careerStats');
Route::get('/memberstats', 'App\Http\Controllers\StatsController@memberStats');
Route::get('/seasonstats', 'App\Http\Controllers\StatsController@seasonStats');
Route::get('/matchstats', 'App\Http\Controllers\StatsController@matchStats');
Route::get('/search', 'App\Http\Controllers\StatsController@search');
Route::get('/settings', 'App\Http\Controllers\StatsController@settings');
Route::get('/seasonleaderboard', 'App\Http\Controllers\StatsController@seasonalLeaderboard');
Route::get('/clubleaderboard', 'App\Http\Controllers\StatsController@clubLeaderboard');
Route::get('/command', 'App\Http\Controllers\StatsController@runCommand');