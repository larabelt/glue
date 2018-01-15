<?php

Route::group([
    'prefix' => 'admin/belt/glue',
    'middleware' => ['web', 'admin']
],
    function () {

        # admin/belt/glue home
        Route::get('{any?}', function () {
            return view('belt-glue::base.admin.dashboard');
        })->where('any', '(.*)');

    }
);