<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Core\Http\Controllers\Behaviors\Positionable;
use Belt\Glue\Category;
use Belt\Glue\Http\Requests;
use Belt\Core\Helpers\MorphHelper;
use Illuminate\Http\Request;

class CategorizablesController extends ApiController
{

    use Positionable;

    /**
     * @var Category
     */
    public $categories;

    /**
     * @var MorphHelper
     */
    public $morphHelper;

    public function __construct(Category $category, MorphHelper $morphHelper)
    {
        $this->categories = $category;
        $this->morphHelper = $morphHelper;
    }

    public function category($id, $categorizable = null)
    {
        $qb = $this->categories->query();

        if ($categorizable) {
            $qb->categoried($categorizable->getMorphClass(), $categorizable->id);
        }

        $category = $qb->where('categories.id', $id)->first();

        return $category ?: $this->abort(404);
    }

    public function categorizable($categorizable_type, $categorizable_id)
    {
        $categorizable = $this->morphHelper->morph($categorizable_type, $categorizable_id);

        return $categorizable ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $categorizable_type, $categorizable_id)
    {

        $owner = $this->categorizable($categorizable_type, $categorizable_id);

        $this->authorize('view', $owner);

        $request = Requests\PaginateCategorizables::extend($request);

        $request->merge([
            'categorizable_id' => $owner->id,
            'categorizable_type' => $owner->getMorphClass()
        ]);

        $paginator = $this->paginator($this->categories->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in glue.
     *
     * @param  Requests\AttachCategory $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AttachCategory $request, $categorizable_type, $categorizable_id)
    {
        $owner = $this->categorizable($categorizable_type, $categorizable_id);

        $this->authorize('update', $owner);

        $id = $request->get('id');

        $category = $this->category($id);

        if ($owner->categories->contains($id)) {
            $this->abort(422, ['id' => ['category already attached']]);
        }

        $owner->categories()->attach($id);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($categorizable_type, $categorizable_id, $id)
    {
        $owner = $this->categorizable($categorizable_type, $categorizable_id);

        $this->authorize('view', $owner);

        $category = $this->category($id, $owner);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from glue.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($categorizable_type, $categorizable_id, $id)
    {
        $owner = $this->categorizable($categorizable_type, $categorizable_id);

        $this->authorize('update', $owner);

        $this->category($id, $owner);

        $owner->categories()->detach($id);

        return response()->json(null, 204);
    }
}
