<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Elastic\ElasticEngine;
use Belt\Glue\Tag;
use Belt\Glue\Elastic\Modifiers\TaggableQueryModifier;
use Illuminate\Database\Eloquent\Collection;

class ElasticTaggableQueryModifierTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::tags
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::modify
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::find
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::params
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::filter
     * @covers \Belt\Glue\Elastic\Modifiers\TaggableQueryModifier::query
     */
    public function test()
    {
        Tag::unguard();

        $engine = m::mock(ElasticEngine::class);

        # tags
        $modifier = new TaggableQueryModifier($engine);
        $this->assertInstanceOf(Tag::class, $modifier->tags());

        # find
        $modifier = new TaggableQueryModifier($engine);
        $tag = factory(Tag::class)->make(['id' => 1]);
        $collection = new Collection([$tag]);
        $tagsRepo = m::mock(Tag::class);
        $tagsRepo->shouldReceive('newQuery')->andReturnSelf();
        $tagsRepo->shouldReceive('whereIn')->with('id', [1])->andReturnSelf();
        $tagsRepo->shouldReceive('orWhereIn')->with('slug', [1])->andReturnSelf();
        $tagsRepo->shouldReceive('get')->andReturn($collection);
        $modifier->tags = $tagsRepo;
        $modifier->find([1]);

        # modify
        $modifier = m::mock(TaggableQueryModifier::class . '[params,filter,query]', [$engine]);
        $modifier->shouldReceive('params')->once()->andReturn([]);
        $modifier->shouldReceive('filter')->once();
        $modifier->shouldReceive('query')->once();
        $modifier->modify(new PaginateRequest(['tag' => '1']));

        # filter
        $params['filter'] = [
            0 => [1],
            1 => [2],
        ];
        $modifier = new TaggableQueryModifier($engine);
        $this->assertFalse(isset($engine->filter[0]['bool']['should']));
        $modifier->filter($params);
        $this->assertTrue(isset($engine->filter[0]['bool']['should']));

        # query
        $params['query'] = [
            0 => [1],
            1 => [2],
        ];
        $modifier = new TaggableQueryModifier($engine);
        $this->assertFalse(isset($engine->query['bool']['should'][0]));
        $modifier->query($params);
        $this->assertTrue(isset($engine->query['bool']['should'][0]));

        # params
        $modifier = m::mock(TaggableQueryModifier::class . '[find]', [$engine]);
        $modifier->shouldReceive('find')->with([1])->andReturn(new Collection([$this->tagFactory(1)]));
        $modifier->shouldReceive('find')->with([4])->andReturn(new Collection([$this->tagFactory(4)]));
        $modifier->shouldReceive('find')->with([7])->andReturn(new Collection([$this->tagFactory(7)]));
        $params = $modifier->params(new PaginateRequest(['tag' => '1+4,~7']));
        //dump($params);
        $this->assertNotEmpty($params['filter']);
        $this->assertEquals([1], $params['filter'][0][0]);
        $this->assertEquals([4], $params['filter'][0][1]);
        $this->assertNotEmpty($params['query']);
        $this->assertEquals([7], $params['query'][1][0]);

    }

    public function tagFactory($id)
    {
        $tag = factory(Tag::class)->make(['id' => $id]);
        $tag->setAppends([]);

        return $tag;
    }

}