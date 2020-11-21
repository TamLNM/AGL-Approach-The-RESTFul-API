<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;
use App\Http\Controllers\API_FireBase;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* 0. Initialize data for firebase */
Route::get('initializeData', [API_FireBase::class, 'initializeData']);

/* 1. Get all the content of post */
//Route::get('getAllPost', [API::class, 'getAllPost']);
Route::get('getAllPost', [API_FireBase::class, 'getAllPost']);

/* 2. Get post by category */
//Route::get('getPostByCategoryTitle', [API::class, 'getPostByCategoryTitle']);
Route::get('getPostByCategoryTitle', [API_FireBase::class, 'getPostByCategoryTitle']);

/* 3. Send request to public a post */
//Route::get('requestNewToken', [API::class, 'RequestNewToken']);
//Route::post('requestNewToken', [API::class, 'requestForPublishnation']);
Route::get('requestNewToken', [API_FireBase::class, 'RequestNewToken']);
Route::post('requestNewToken', [API_FireBase::class, 'requestForPublishnation']);

// Route::get('requestPostForm', [API::class, 'requestPostForm']);
// Route::post('requestPostForm', [API::class, 'requestForPublishnation']);
Route::get('requestPostForm', [API_FireBase::class, 'requestPostForm']);
Route::post('requestPostForm', [API_FireBase::class, 'requestForPublishnation']);

/* 4. Get the lastest post */
Route::get('getLastestPost', [API_FireBase::class, 'getLastestPost']);

