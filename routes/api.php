<?php

use Belt\Glue\Http\Controllers\Api;

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
        Route::get('tags/{tag}', Api\TagsController::class . '@show');
        Route::put('tags/{tag}', Api\TagsController::class . '@update');
        Route::delete('tags/{tag}', Api\TagsController::class . '@destroy');
        Route::get('tags', Api\TagsController::class . '@index');
        Route::post('tags', Api\TagsController::class . '@store');

        # taggables
        Route::group([
            'prefix' => '{taggable_type}/{taggable_id}/tags',
            'middleware' => 'request.injections:taggable_type,taggable_id'
        ], function () {
            Route::get('{id}', Api\TaggablesController::class . '@show');
            Route::put('{id}', Api\TaggablesController::class . '@update');
            Route::delete('{id}', Api\TaggablesController::class . '@destroy');
            Route::get('', Api\TaggablesController::class . '@index');
            Route::post('', Api\TaggablesController::class . '@store');
        });
    }
);
