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

Route::group(['middleware' => 'web'], function () {
    Route::get('/login', 'Auth\LoginController@login');
    Route::get('/logout', 'Auth\LoginController@logout');
    Route::get('/{raffle}', 'RaffleEntriesController@registration')->name('registration');

    Route::post('/raffle/signup', 'RaffleEntriesController@signupPlus');
    Route::post('/r/{raffle}/{raffle_id}', 'RaffleEntriesController@register')->name('register');
    Route::match(['GET', 'POST'], '/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    // raffles
    Route::get('/', 'RafflesController@index');
    Route::get('/raffle/create', 'RafflesController@create');
    Route::get('/raffle/edit', 'RafflesController@edit');
    Route::get('/raffle/winners', 'RafflesController@winners');
    Route::get('/raffle/reload/list', 'RafflesController@reload');

    Route::post('/draw/{raffle_id}', 'RaffleEntriesController@draw');
    Route::post('/raffle/archive', 'RafflesController@archive');
    Route::post('/raffle/update', 'RafflesController@updates');
    Route::post('/raffle/closed', 'RafflesController@closed');
    Route::post('/raffle/search', 'RafflesController@search');
    Route::resource('raffle', 'RafflesController');

    Route::get('/raffle-entries/{raffle_id}', 'RaffleEntriesController@getRaffleEntries');

    // prizes
    Route::get('/prizes/list', 'PrizesController@index');
    Route::get('/prizes/create', 'PrizesController@create');
    Route::get('/prizes/edit', 'PrizesController@edit');
    Route::get('/prizes/selected/row/{prize_id}', 'PrizesController@createSelectedRow');
    Route::get('/prizes/reload/list', 'PrizesController@reloadList');
    Route::get('/prizes/assign/list/{raffle_id}', 'PrizesController@getRafflePrizes');

    Route::post('/prizes/assign', 'PrizesController@assign');
    Route::post('/prizes/upload', 'PrizesController@upload');
    Route::post('/prizes/updates', 'PrizesController@updatePrize');
    Route::post('/prizes/search', 'PrizesController@search');
    Route::delete('/prizes/delete/{raffle_id}/{prize_id}', 'PrizesController@deassignPrize');

    Route::resource('prizes', 'PrizesController');

    // actions
    Route::get('/actions/list', 'ActionsController@index');
    Route::get('/actions/reload/list', 'ActionsController@reloadList');
    Route::get('/actions/selected/row/{action_idi}', 'ActionsController@createSelectedRow');
    Route::get('/actions/assign/list/{raffle_id}', 'ActionsController@getRaffleActions');

    Route::post('/actions/assign', 'ActionsController@assign');
    Route::delete('/actions/delete/{raffle_id}/{raffle_action_id}', 'ActionsController@deassignAction');

    Route::resource('actions', 'ActionsController');

    // configurations
    Route::put('/update/auto-draw', 'RafflesController@updateAutoDraw');
});
