<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObemMainController;
use App\Http\Controllers\ObemSiteMediaController;

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

Route::get('/obem_main/home', [ObemMainController::class, 'home']);
Route::post(
    '/obem_site_media/upload_media', 
    [ObemSiteMediaController::class, 'upload_media']
);