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

Route::get('/', function () {
    return view('welcome');
});

    Route::group(
        [
            'prefix' => 'contact',
        ],
        function () {
            Route::get('', [
                'as'   => 'contact.index',
                'uses' => 'ContactController@index'
            ]);

            Route::post('', [
                'as'   => 'contact.send',
                'uses' => 'ContactController@send'
            ]);
        }
    );


    Route::group(
        [
            'prefix' => 'blog',
        ],
        function () {
            Route::get('', [
                'as'   => 'blog.index',
                'uses' => 'ArticleController@blogIndex'
            ]);
            Route::get('categorie/{slug}', [
                'as'   => 'blog.category',
                'uses' => 'ArticleController@blogCategorie'
            ]);
            Route::get('{slug}', [
                'as'   => 'blog.show',
                'uses' => 'ArticleController@blogDetail'
            ]);
        }
    );





    // Catch all route : à mettre en dernier
    Route::get('{slug}', [
        'as'   => 'article',
        'uses' => 'ArticleController@index'
    ]);
