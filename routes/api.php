<?php

use Belt\Glue\Http\Controllers\Api;

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

Route::group([
    'prefix' => 'api/v1',
    'middleware' => ['api']
],
    function () {

        # categories
        Route::get('categories/{category}', Api\CategoriesController::class . '@show');
        Route::put('categories/{category}', Api\CategoriesController::class . '@update');
        Route::delete('categories/{category}', Api\CategoriesController::class . '@destroy');
        Route::get('categories', Api\CategoriesController::class . '@index');
        Route::post('categories', Api\CategoriesController::class . '@store');

        # categorizables
        Route::group([
            'prefix' => '{categorizable_type}/{categorizable_id}/categories',
            'middleware' => 'request.injections:categorizable_type,categorizable_id'
        ], function () {
            Route::get('{id}', Api\CategorizablesController::class . '@show');
            Route::put('{id}', Api\CategorizablesController::class . '@update');
            Route::delete('{id}', Api\CategorizablesController::class . '@destroy');
            Route::get('', Api\CategorizablesController::class . '@index');
            Route::post('', Api\CategorizablesController::class . '@store');
        });

        # tags
        Route::get('tags/{id}', Api\TagsController::class . '@show');
        Route::put('tags/{id}', Api\TagsController::class . '@update');
        Route::delete('tags/{id}', Api\TagsController::class . '@destroy');
        Route::get('tags', Api\TagsController::class . '@index');
        Route::post('tags', Api\TagsController::class . '@store');

        # taggables
        Route::group(['prefix' => 'taggables/{taggable_type}/{taggable_id}'], function () {
            Route::get('{id}', Api\TaggablesController::class . '@show');
            Route::delete('{id}', Api\TaggablesController::class . '@destroy');
            Route::get('', Api\TaggablesController::class . '@index');
            Route::post('', Api\TaggablesController::class . '@store');
        });
    }
);
