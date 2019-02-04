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

Route::post(\Telegram::getAccessToken(),function (){
    app('App\Http\Controllers\TelegramController')->webhook();
});


Route::post('/viber_bot', 'ViberController@webHook');
Route::get('/viber_bot', 'ViberController@webHook');

Route::get('/setWebHook', 'ViberController@setWebhook');

Route::get('/set', 'TestController@setWebHook');
Route::get('/get', 'TestController@getWebHookInfo');