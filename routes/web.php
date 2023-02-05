<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', [\App\Http\Controllers\NewsController::class, 'showPhpInfo']);

Route::get('/news', [\App\Http\Controllers\NewsController::class, 'showAll']);
Route::get('/news/post/{id}', [\App\Http\Controllers\NewsController::class, 'showOne'])->name('news.post');


