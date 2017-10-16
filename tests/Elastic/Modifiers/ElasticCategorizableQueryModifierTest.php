<?php

use Mockery as m;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Elastic\ElasticEngine;
use Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier;

class ElasticCategorizableQueryModifierTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Elastic\Modifiers\CategorizableQueryModifier::modify
     */
    public function test()
    {
        $engine = m::mock(ElasticEngine::class);
        $modifier = new CategorizableQueryModifier($engine);

        $this->assertFalse(isset($engine->filter[0]['bool']['must'][0]['terms']['categories']));

        $modifier->modify(new PaginateRequest(['category' => '1,2']));
        $this->assertTrue(isset($engine->filter[0]['bool']['must'][0]['terms']['categories']));
    }

}