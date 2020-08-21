<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login','AuthController@login');
Route::post('register','AuthController@register');
Route::get('domaines','domaineController@index');
Route::post('listDomaine','listdomaineController@store');
Route::get('listDomaine','listdomaineController@index');
Route::delete('listDomaine/{id}','listdomaineController@delete');
Route::get('allPublication','publicationController@allPub');
Route::get('rechercheallPublication/{texte}','publicationController@rechercheallPub');
Route::get('pubsDomaine/{id}','publicationController@pubsDomaine');
Route::group(['middleware'=>'auth.jwt'],function()
{
    Route::post('setMyProfile','AuthController@setMyProfile');
    Route::get('myProfile','AuthController@myProfile');
    Route::post('logout','AuthController@logout');
    Route::get('publication','publicationController@index');
    Route::get('publication/{id}','publicationController@show');
    Route::post('publication','publicationController@store');
    Route::get('myPublications','publicationController@myPublications');
    Route::post('publication/{id}','publicationController@update');
    Route::delete('publication/{id}','publicationController@delete');
    Route::post('comment','commentController@store');
    Route::get('comment/{id}','commentController@show');
});

