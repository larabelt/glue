<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Elastic\ElasticEngine;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;
use Belt\Glue\Tag;
use Belt\Glue\Elastic\Modifiers\TaggableModifierTrait;
use Illuminate\Database\Eloquent\Collection;

class ElasticTaggableModifierTraitTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableModifierTrait::tags
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableModifierTrait::find
     */
    public function test()
    {
        Tag::unguard();

        $engine = m::mock(ElasticEngine::class);

        # tags
        $modifier = new TaggableModifierTraitStub($engine);
        $this->assertInstanceOf(Tag::class, $modifier->tags());

        # find
        $modifier = new TaggableModifierTraitStub($engine);
        $tag = factory(Tag::class)->make(['id' => 1]);
        $collection = new Collection([$tag]);
        $tagsRepo = m::mock(Tag::class);
        $tagsRepo->shouldReceive('newQuery')->andReturnSelf();
        $tagsRepo->shouldReceive('whereIn')->with('id', [1])->andReturnSelf();
        $tagsRepo->shouldReceive('orWhereIn')->with('slug', [1])->andReturnSelf();
        $tagsRepo->shouldReceive('get')->andReturn($collection);
        $modifier->tags = $tagsRepo;
        $modifier->find([1]);

    }

    public function tagFactory($id)
    {
        $tag = factory(Tag::class)->make(['id' => $id]);
        $tag->setAppends([]);

        return $tag;
    }

}

class TaggableModifierTraitStub extends PaginationQueryModifier
{
    use TaggableModifierTrait;

    /**
     * Modify the query
     *
     * @param  PaginateRequest $request
     * @return $query
     */
    public function modify(PaginateRequest $request)
    {

    }
}