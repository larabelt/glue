<?php

namespace Belt\Glue\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests;

class TagsController extends ApiController
{

    /**
     * @var Tag
     */
    public $tags;

    /**
     * ApiController constructor.
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tags = $tag;
    }

    public function get($id)
    {
        return $this->tags->find($id) ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\PaginateTags $request)
    {

        $this->authorize('index', Tag::class);

        $paginator = $this->paginator($this->tags->query(), $request->reCapture());

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
            'name' => $input['name'],
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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = $this->get($id);

        $this->authorize('index', $tag);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateTag $request
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateTag $request, $id)
    {
        $tag = $this->get($id);

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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = $this->get($id);

        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json(null, 204);
    }
}
