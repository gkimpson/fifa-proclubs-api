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
    Route::get('/dashboard', 'App\Http\Controllers\MyDashboardController@index')->middleware(['auth'])->name('dashboard'); 
    Route::get('/club', 'App\Http\Controllers\MyDashboardController@club')->middleware(['auth'])->name('club');
    Route::get('/squad', 'App\Http\Controllers\MyDashboardController@squad')->middleware(['auth'])->name('squad');
    Route::get('/league', 'App\Http\Controllers\MyDashboardController@league')->middleware(['auth'])->name('league');
    Route::get('/cup', 'App\Http\Controllers\MyDashboardController@cup')->middleware(['auth'])->name('cup');
    Route::get('/league/form', 'App\Http\Controllers\MyDashboardController@form')->middleware(['auth'])->name('leagueform');
    Route::get('/league/rank', 'App\Http\Controllers\MyDashboardController@rank')->middleware(['auth'])->name('leaguerank');
    Route::get('/cup/form', 'App\Http\Controllers\MyDashboardController@form')->middleware(['auth'])->name('cupform');
    Route::get('/cup/rank', 'App\Http\Controllers\MyDashboardController@rank')->middleware(['auth'])->name('cuprank');
    Route::get('/media', 'App\Http\Controllers\MyDashboardController@media')->middleware(['auth'])->name('media');  
});

require __DIR__.'/auth.php';


/** potentially make this open to all - would just be pulling data from the EA endpoint anyway... */
Route::prefix('22')->group(function () {
    Route::get('/club/{platform}/{clubId}/{matchType?}', 'App\Http\Controllers\StatsController@matches');
    Route::get('/club/{platform}/{clubId}/{matchType?}/form', 'App\Http\Controllers\StatsController@matchesForm');
    Route::get('/club/{platform}/{clubId}/{matchType?}/ranking', 'App\Http\Controllers\StatsController@matchesRanking');
    Route::get('/squad/{platform}/{clubId}', 'App\Http\Controllers\StatsController@squad');
    Route::get('/squad/{platform}/{clubId}/ranking', 'App\Http\Controllers\StatsController@squadRanking');
    Route::get('/squad/{platform}/{clubId}/compare-players', 'App\Http\Controllers\StatsController@comparePlayers');
    Route::get('/compare-clubs/{platform}/{clubId1}/{clubId2}', 'App\Http\Controllers\StatsController@compareClubs');
    Route::get('/compare-clubs/{platform}/{clubId1}/{clubId2}/league', 'App\Http\Controllers\StatsController@compareClubsForm');
    Route::get('/compare-clubs/{platform}/{clubId1}/{clubId2}/cup', 'App\Http\Controllers\StatsController@compareClubsForm');
    Route::get('/compare-clubs/{platform}/{clubId1}/{clubId2}/squads', 'App\Http\Controllers\StatsController@compareSquads');
    Route::get('/media/{platform}/{clubId}', 'App\Http\Controllers\StatsController@media');
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

Route::get('/debug', 'App\Http\Controllers\StatsController@debug');

Route::post('highlights', 'App\Http\Controllers\StatsController@highlights');

/** now redundant */
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