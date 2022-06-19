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
Route::post(
    '/obem_site_media/update_article/{id}',
    [ObemSiteMediaController::class, 'update_article']
);
Route::post(
    '/obem_site_media/create_article/{article_guid?}',
    [ObemSiteMediaController::class, 'create_article']
);
Route::post(
    '/obem_site_media/update_media/{id}',
    [ObemSiteMediaController::class, 'update_media']
);
Route::post(
    '/obem_site_media/page_info/{article_guid}',
    [ObemSiteMediaController::class, 'page_info']
);
Route::get(
    '/obem_site_media/new_article/{id?}/{article_guid?}',
    [ObemSiteMediaController::class, 'new_article']
);
Route::get(
    '/obem_site_media/new_media/{id?}',
    [ObemSiteMediaController::class, 'new_media']
);
Route::get(
    '/obem_site_media/show_medium/{id}',
    [ObemSiteMediaController::class, 'show_medium']
);
Route::get(
    '/obem_site_media/serve_medium/{id}',
    [ObemSiteMediaController::class, 'serve_medium']
);
Route::get(
    '/obem_site_media/show_article/{id}',
    [ObemSiteMediaController::class, 'show_article']
);
Route::get(
    '/obem_site_media/articles_index/{page_number}/{article_guid}',
    [ObemSiteMediaController::class, 'articles_index']
);

