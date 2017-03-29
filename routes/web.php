<?php

use Belt\Glue\Http\Controllers\Web;

Route::group(['middleware' => ['web']], function () {

    # categories
    Route::get('categories/{category}/preview', Web\CategoriesController::class . '@preview');
    Route::get('categories/{category}', Web\CategoriesController::class . '@show');
    Route::get('categories', function () {
        return view('belt-core::base.web.home');
    });

});