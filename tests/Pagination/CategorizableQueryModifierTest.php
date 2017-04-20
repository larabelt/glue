<?php

use Mockery as m;
use Belt\Core\Testing;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Glue\Pagination\CategorizableQueryModifier;
use Illuminate\Database\Eloquent\Builder;

class CategorizableQueryModifierTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Pagination\CategorizableQueryModifier::modify
     */
    public function test()
    {
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('hasCategory')->once()->with(1);

        $request = new PaginateRequest(['category' => '1']);

        CategorizableQueryModifier::modify($qb, $request);
    }

}