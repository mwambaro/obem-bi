<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ObemMainController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\ObemSiteMediaController;
use App\Http\Controllers\EmploymentFoldersController;

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

Route::get(
    '/', 
    [ObemMainController::class, 'home']
);

Route::post(
    '/obem_main/locale', 
    [ObemMainController::class, 'locale']
);
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
Route::post(
    '/employment_folders/update_employment_folder/{id}',
    [EmploymentFoldersController::class, 'update_employment_folder']
);
Route::post(
    '/employment_folders/create_employment_folder',
    [EmploymentFoldersController::class, 'create_employment_folder']
);
Route::post(
    '/users/validate_user/{id}',
    [UsersController::class, 'validate_user']
);
Route::post(
    '/users/update_user/{id}',
    [UsersController::class, 'update_user']
);
Route::post(
    '/users/create_user',
    [UsersController::class, 'create_user']
);
Route::post(
    '/users/sign_in',
    [UsersController::class, 'sign_in']
);
Route::post(
    '/users/create_profile_photo/{id}',
    [UsersController::class, 'create_profile_photo']
);
Route::get(
    '/obem_main/home', 
    [ObemMainController::class, 'home']
);
Route::get(
    '/obem_main/orientation',
    [ObemMainController::class, 'orientation']
);
Route::get(
    '/page_views/analytics',
    [PageViewController::class, 'index']
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
Route::get(
    '/obem_main/community',
    [ObemMainController::class, 'community']
);
Route::get(
    '/obem_main/about',
    [ObemMainController::class, 'about']
);
Route::get(
    '/obem_main/terms_of_use',
    [ObemMainController::class, 'terms_of_use']
);
Route::get(
    '/obem_main/privacy',
    [ObemMainController::class, 'privacy']
);
Route::get(
    '/obem_main/cookies',
    [ObemMainController::class, 'cookies']
);
Route::get(
    '/obem_main/contacts',
    [ObemMainController::class, 'contacts']
);
Route::get(
    '/employment_folders/new_employment_folder/{id?}/{new_user_id?}',
    [EmploymentFoldersController::class, 'new_employment_folder']
);
Route::get(
    '/employment_folders/serve_cv/{id}',
    [EmploymentFoldersController::class, 'serve_cv']
);
Route::get(
    '/employment_folders/serve_cover_letter/{id}',
    [EmploymentFoldersController::class, 'serve_cover_letter']
);
Route::get(
    '/employment_folders/delete_employment_folder/{id}',
    [EmploymentFoldersController::class, 'delete_employment_folder']
);
Route::get(
    '/users/new_user/{id?}',
    [UsersController::class, 'new_user']
);
Route::get(
    '/users/new_sign_in',
    [UsersController::class, 'new_sign_in']
);
Route::get(
    '/users/show_user/{id}/{view_mode_str?}',
    [UsersController::class, 'show_user']
);
Route::get(
    '/users/sign_out',
    [UsersController::class, 'sign_out']
);
Route::get(
    '/users/index',
    [UsersController::class, 'index']
);
Route::get(
    '/users/delete_user/{id}',
    [UsersController::class, 'delete_user']
);

