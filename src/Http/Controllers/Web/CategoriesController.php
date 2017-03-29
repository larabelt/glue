<?php

namespace Belt\Glue\Http\Controllers\Web;

use Auth;
use Belt\Content\Services\CompileService;
use Belt\Core\Http\Controllers\BaseController;
use Belt\Glue\Category;

/**
 * Class CategoriesController
 * @package Belt\Glue\Http\Controllers\Web
 */
class CategoriesController extends BaseController
{

    /**
     * @var CompileService
     */
    public $service;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->service = new CompileService();

        $this->middleware('web');
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     *
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {

        $method = $this->env('APP_DEBUG') ? 'compile' : 'cache';

        /**
         * @todo below does not work on "handled" routes
         */
        if ($method == 'cache' && Auth::user()) {
            try {
                $this->authorize('update', $category);
                $method = 'compile';
            } catch (\Exception $e) {

            }
        }

        $compiled = $this->service->$method($category);

        return view('belt-glue::categories.web.show', compact('category', 'compiled'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     *
     * @return \Illuminate\View\View
     */
    public function preview(Category $category)
    {

        $this->authorize('update', $category);

        $compiled = $this->service->compile($category);

        return view('belt-glue::categories.web.show', compact('category', 'compiled'));
    }

}