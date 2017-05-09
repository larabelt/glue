<?php

namespace Belt\Glue\Http\Controllers\Web;

use Belt\Content\Http\Controllers\Compiler;
use Belt\Core\Http\Controllers\BaseController;
use Belt\Glue\Category;

/**
 * Class CategoriesController
 * @package Belt\Glue\Http\Controllers\Web
 */
class CategoriesController extends BaseController
{

    use Compiler;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
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

        $compiled = $this->compile($category);

        $owner = $category;

        return view('belt-glue::categories.web.show', compact('owner', 'category', 'compiled'));
    }

}