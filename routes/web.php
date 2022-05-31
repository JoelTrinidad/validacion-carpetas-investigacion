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



Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/import','CarpetasInvestigacionController@importFile')->name('import');
Route::post('/import','CarpetasInvestigacionController@importExcel');
Route::post('/import/timeout','CarpetasInvestigacionController@timeout');
