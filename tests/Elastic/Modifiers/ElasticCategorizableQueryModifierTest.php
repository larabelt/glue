<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Elastic\ElasticEngine;
use Belt\Glue\Category;
use Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier;
use Illuminate\Database\Eloquent\Collection;

class ElasticCategorizableQueryModifierTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::categories
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::modify
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::find
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::params
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::filter
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::query
     */
    public function test()
    {
        Category::unguard();

        $engine = m::mock(ElasticEngine::class);

        # categories
        $modifier = new CategorizableQueryModifier($engine);
        $this->assertInstanceOf(Category::class, $modifier->categories());

        # find
        $modifier = new CategorizableQueryModifier($engine);
        $category = factory(Category::class)->make(['id' => 1]);
        $collection = new Collection([$category]);
        $categoriesRepo = m::mock(Category::class);
        $categoriesRepo->shouldReceive('newQuery')->andReturnSelf();
        $categoriesRepo->shouldReceive('whereIn')->with('id', [1])->andReturnSelf();
        $categoriesRepo->shouldReceive('orWhereIn')->with('slug', [1])->andReturnSelf();
        $categoriesRepo->shouldReceive('get')->andReturn($collection);
        $modifier->categories = $categoriesRepo;
        $modifier->find([1]);

        # modify
        $modifier = m::mock(CategorizableQueryModifier::class . '[params,filter,query]', [$engine]);
        $modifier->shouldReceive('params')->once()->andReturn([]);
        $modifier->shouldReceive('filter')->once();
        $modifier->shouldReceive('query')->once();
        $modifier->modify(new PaginateRequest(['category' => '1']));

        # filter
        $params['filter'] = [
            1 => [
                [1, 2, 3],
                [4, 5, 6],
            ],
            2 => [7, 8, 9]
        ];
        $modifier = new CategorizableQueryModifier($engine);
        $this->assertFalse(isset($engine->filter[0]['bool']['should']));
        $modifier->filter($params);
        $this->assertTrue(isset($engine->filter[0]['bool']['should']));

        # query
        $params['query'] = [
            1 => [
                [1, 2, 3],
                [4, 5, 6],
            ],
            2 => [7, 8, 9]
        ];
        $modifier = new CategorizableQueryModifier($engine);
        $this->assertFalse(isset($engine->query['bool']['should'][0]));
        $modifier->query($params);
        $this->assertTrue(isset($engine->query['bool']['should'][0]));

        # params
        $modifier = m::mock(CategorizableQueryModifier::class . '[find]', [$engine]);
        $modifier->shouldReceive('find')->with([1])->andReturn([$this->categoryFactory(1, [2, 3])]);
        $modifier->shouldReceive('find')->with([4])->andReturn([$this->categoryFactory(4, [5, 6])]);
        $modifier->shouldReceive('find')->with([7])->andReturn([$this->categoryFactory(7, [8, 9])]);
        $params = $modifier->params(new PaginateRequest(['category' => '1+4,~7']));
        //dump($params);
        $this->assertNotEmpty($params['filter']);
        $this->assertEquals([1, 2, 3], $params['filter'][0][0]);
        $this->assertEquals([4, 5, 6], $params['filter'][0][1]);
        $this->assertNotEmpty($params['query']);
        $this->assertEquals([7, 8, 9], $params['query'][1][0]);

    }

    public function categoryFactory($id, $child_ids = [], $parent = null)
    {
        $category = factory(Category::class)->make(['id' => $id]);
        $category->setAppends([]);
        $category->descendants = new Collection();

        foreach ($child_ids as $child_id) {
            $child = $this->categoryFactory($child_id, [], $category);
            $category->descendants->add($child);
        }

        return $category;
    }

}