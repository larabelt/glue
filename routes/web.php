<?php

use Belt\Glue\Http\Controllers\Web;

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

Route::group(['middleware' => ['web']], function () {

    # pages
    Route::get('page/{page}/preview', Web\PagesController::class . '@preview');
    Route::get('page/{page}', Web\PagesController::class . '@show');
    Route::get('pages', function () {
        return view('belt-core::base.web.home');
    });

});