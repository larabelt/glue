<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Glue\Category;
use Belt\Glue\Http\Requests;

class CategoriesController extends ApiController
{

    /**
     * @var Category
     */
    public $category;

    /**
     * ApiController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->categories = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\PaginateCategories $request)
    {
        $this->authorize('index', Category::class);

        $paginator = $this->paginator($this->categories->query(), $request->reCapture());

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreCategory $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreCategory $request)
    {
        $this->authorize('create', Category::class);

        $input = $request->all();

        $category = $this->categories->create([
            'parent_id' => $request->get('parent_id'),
            'name' => $request->get('name'),
        ]);

        $this->set($category, $input, [
            'page_id',
            'parent_id',
            'template',
            'header',
            'body',
            'footer',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ]);

        $category->save();

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateCategory $request
     * @param  Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateCategory $request, Category $category)
    {
        $this->authorize('update', $category);

        $input = $request->all();

        $this->set($category, $input, [
            'template',
            'name',
            'slug',
            'body',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ]);

        $category->save();

        return response()->json($category);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(null, 204);
    }
}
