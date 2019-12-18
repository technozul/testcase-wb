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

Route::get('/', 'TestcaseWowbid@index');
Route::get('test-case-1', 'TestcaseWowbid@testcase_1');
Route::get('test-case-2', 'TestcaseWowbid@testcase_2');
Route::get('about', 'TestcaseWowbid@about');