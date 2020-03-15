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


use Login\Middleware\Middlware;


//Route::get('/login', function () {
//    return view('Login::index');
//})->middleware(Middlware::class);

Route::get('/login/', '\Login\Controllers\Login@execute')->middleware(Middlware::class);

Route::post('/login/', '\Login\Controllers\LoginAuth@execute')->middleware(Middlware::class);

Route::get('/login/register/', '\Login\Controllers\Register@execute')->middleware(Middlware::class);

Route::post('/login/register/', '\Login\Controllers\RegisterAuth@execute')->middleware(Middlware::class);

Route::get('/login/fbauth/', '\Login\Controllers\FbAuth@execute')->middleware(Middlware::class);

Route::get('/login/googleauth/', '\Login\Controllers\GoogleAuth@execute')->middleware(Middlware::class);

