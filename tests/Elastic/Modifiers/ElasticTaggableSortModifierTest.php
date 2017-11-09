<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Elastic\ElasticEngine;
use Belt\Glue\Tag;
use Belt\Glue\Elastic\Modifiers\TaggableSortModifier;
use Illuminate\Database\Eloquent\Collection;

class ElasticTaggableSortModifierTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableSortModifier::modify
     */
    public function test()
    {
        $engine = m::mock(ElasticEngine::class);
        $modifier = m::mock(TaggableSortModifier::class . '[find]', [$engine]);
        $modifier->shouldReceive('find')->andReturn($this->tagFactory(44));

        $this->assertFalse(isset($engine->sort['_script']));
        $modifier->modify(new PaginateRequest(['orderBy' => 'tag:44']));
        $this->assertTrue(isset($engine->sort['_script']));
    }

    public function tagFactory($id)
    {
        $tag = factory(Tag::class)->make(['id' => $id]);
        $tag->setAppends([]);

        return new Collection([$tag]);
    }

}