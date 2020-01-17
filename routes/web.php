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

Route::get('/', 'MainController@index')->name('home');
Route::get('/encriptador', 'MainController@encriptador');
Route::get('/marcador', 'MainController@marcador');
//AJAX
Route::post('/marcadorCheck', 'MainController@marcadorCheck');
Route::post('/encriptadorCheck', 'MainController@encriptadorCheck');
