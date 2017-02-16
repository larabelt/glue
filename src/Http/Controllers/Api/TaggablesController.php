<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests;
use Belt\Core\Helpers\MorphHelper;
use Illuminate\Database\Eloquent\Model;

class TaggablesController extends ApiController
{

    /**
     * @var Tag
     */
    public $tags;

    /**
     * @var Model
     */
    public $taggable;

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

        $taggable = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('view', $taggable);

        $request->merge([
            'taggable_id' => $taggable->id,
            'taggable_type' => $taggable->getMorphClass()
        ]);

        $paginator = $this->paginator($this->tags->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\AttachTag $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AttachTag $request, $taggable_type, $taggable_id)
    {
        $taggable = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('update', $taggable);

        $id = $request->get('id');

        if ($taggable->tags->contains($id)) {
            $this->abort(422, ['id' => ['tag already attached']]);
        }

        $taggable->tags()->attach($id);

        return response()->json($this->tag($id), 201);
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
        $taggable = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('view', $taggable);

        $tag = $this->tag($id, $taggable);

        return response()->json($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($taggable_type, $taggable_id, $id)
    {
        $taggable = $this->taggable($taggable_type, $taggable_id);

        $this->authorize('update', $taggable);

        if (!$taggable->tags->contains($id)) {
            $this->abort(422, ['id' => ['tag not attached']]);
        }

        $taggable->tags()->detach($id);

        return response()->json(null, 204);
    }
}
