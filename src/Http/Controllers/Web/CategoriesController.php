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
        if (!$category->is_active) {
            abort(404);
        }

        $compiled = $this->compile($category);

        $owner = $category;

        $view = $category->getTemplateConfig('extends', 'belt-glue::categories.web.show');

        return view($view, compact('owner', 'category', 'compiled'));
    }

}