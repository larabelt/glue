<?php

use Mockery as m;
use Belt\Core\Testing;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Glue\Pagination\TaggableQueryModifier;
use Illuminate\Database\Eloquent\Builder;

class TaggableQueryModifierTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Pagination\TaggableQueryModifier::modify
     */
    public function test()
    {
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('hasTag')->once()->with(1);

        $request = new PaginateRequest(['tag' => '1']);

        TaggableQueryModifier::modify($qb, $request);
    }

}