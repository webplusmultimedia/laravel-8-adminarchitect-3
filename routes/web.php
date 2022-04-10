<?php

    use App\Http\Controllers\ArticleController;
    use App\Http\Controllers\ContactController;
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
            Route::get('', [ContactController::class, 'index'])->name('contact.index');

            Route::post('', [ContactController::class, 'send'])->name('contact.send');
        }
    );


    Route::group(
        [
            'prefix' => 'blog',
        ],
        function () {
            Route::get('', [ArticleController::class, 'blogIndex'])->name('blog.index');

            Route::get('{slug}', [ArticleController::class, 'blogShow'])->name('blog.show');

        }
    );


    // Catch all route : à mettre en dernier
    Route::get('{slug}', [ArticleController::class, 'index'])->name('page');
