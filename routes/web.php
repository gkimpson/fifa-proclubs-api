<?php

use Illuminate\Support\Facades\Route;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

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

Route::group(['middleware' => 'auth'], function() {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->middleware(['auth'])->name('dashboard');

    Route::get('/dashboard', 'App\Http\Controllers\MyDashboardController@index')->middleware(['auth'])->name('dashboard');    
});

require __DIR__.'/auth.php';


Route::prefix('22')->group(function () {
    Route::get('/club/{platform}/{clubId}/{matchType?}', 'App\Http\Controllers\ClubController@index');
    Route::get('/squad/{platform}/{clubId}', 'App\Http\Controllers\StatsController@squad');
    Route::get('/squad/compare/{platform}/{clubId}', 'App\Http\Controllers\ClubController@compare');
});


/** backend/postman etc.. */
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


Route::get('/overview', 'App\Http\Controllers\StatsController@overviewScrape');


Route::get('/scrape', function() {
    $client = new Client();
    $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Linux i686; rv:78.0) Gecko/20100101 Firefox/78.0');
    $client->setServerParameter('REFERER', 'https://www.ea.com/');
    
    $url = 'https://www.ea.com/en-gb/games/fifa/pro-clubs/ps5-xbsxs/overview?clubId=2552&platform=ps5';
    $crawler = $client->request('GET', $url);
    dd($crawler->html());
    // $customCrestBaseUrl = $crawler
    // ->filter('ea-proclub-overview')
    // ->first()
    // ->extract(['custom-crest-base-url', 'endpoints', 'colors', 'match-type', 'headers-labels', 'divison-labels', 'progressbar-labels', 'members-labels', 'match-labels', 'trophies-labels', 'history-labels', 'translations', 'crest-base-url', 'custom-crest-base-url', 'default-crest-url', 'loading-image', 'default-club-name']);
    // dd($customCrestBaseUrl);
});