<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Core\Http\Controllers\Behaviors\Positionable;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests;
use Belt\Core\Helpers\MorphHelper;

class TaggablesController extends ApiController
{

    use Positionable;

    /**
     * @var Tag
     */
    public $tags;

    /**
     * @var MorphHelper
     */
    public $morphHelper;

    public function __construct(Tag $tag, MorphHelper $morphHelper)
    {
        $this->tags = $tag;
        $this->morphHelper = $morphHelper;
    }

    public function tag($id, $taggable = null)
    {
        $qb = $this->tags->query();

        if ($taggable) {
            $qb->tagged($taggable->getMorphClass(), $taggable->id);
        }

        $tag = $qb->where('tags.id', $id)->first();

        return $tag ?: $this->abort(404);
    }

    public function taggable($taggable_type, $taggable_id)
    {
        $taggable = $this->morphHelper->morph($taggable_type, $taggable_id);

        return $taggable ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\PaginateTaggables $request, $taggable_type, $taggable_id)
    {

        $request->reCapture();

        $owner = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('view', $owner);

        $request->merge([
            'taggable_id' => $owner->id,
            'taggable_type' => $owner->getMorphClass()
        ]);

        $paginator = $this->paginator($this->tags->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in glue.
     *
     * @param  Requests\AttachTag $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AttachTag $request, $taggable_type, $taggable_id)
    {
        $owner = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('update', $owner);

        $id = $request->get('id');

        $tag = $this->tag($id);

        if ($owner->tags->contains($id)) {
            $this->abort(422, ['id' => ['tag already attached']]);
        }

        $owner->tags()->attach($id);

        return response()->json($tag, 201);
    }

    /**
     * Update the specified resource in glue.
     *
     * @param  Requests\UpdateTaggable $request
     * @param  string $taggable_type
     * @param  string $taggable_id
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateTaggable $request, $taggable_type, $taggable_id, $id)
    {
        $owner = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('update', $owner);

        $tag = $this->tag($id, $owner);

        $this->repositionEntity($request, $id, $owner->tags, $owner->tags());

        return response()->json($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($taggable_type, $taggable_id, $id)
    {
        $owner = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('view', $owner);

        $tag = $this->tag($id, $owner);

        return response()->json($tag);
    }

    /**
     * Remove the specified resource from glue.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($taggable_type, $taggable_id, $id)
    {
        $owner = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('update', $owner);

        $this->tag($id, $owner);

        $owner->tags()->detach($id);

        return response()->json(null, 204);
    }
}
