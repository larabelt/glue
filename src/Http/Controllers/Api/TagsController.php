<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests;
use Illuminate\Http\Request;

class TagsController extends ApiController
{

    /**
     * @var Tag
     */
    public $tag;

    /**
     * ApiController constructor.
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tags = $tag;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Tag::class);

        $request = Requests\PaginateTags::extend($request);

        $paginator = $this->paginator($this->tags->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreTag $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreTag $request)
    {
        $this->authorize('create', Tag::class);

        $input = $request->all();

        $tag = $this->tags->create([
            'name' => $request->get('name'),
        ]);

        $this->set($tag, $input, [
            'slug',
            'body',
        ]);

        $tag->save();

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tag $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateTag $request
     * @param  Tag $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateTag $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $input = $request->all();

        $this->set($tag, $input, [
            'name',
            'slug',
            'body',
        ]);

        $tag->save();

        return response()->json($tag);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json(null, 204);
    }
}
