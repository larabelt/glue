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
        $qb->shouldReceive('inCategory')->once()->with(2);

        $request = new PaginateRequest(['category' => '1', 'in_category' => 2]);

        $modifer = new CategorizableQueryModifier($qb, $request);
        $modifer->modify($qb, $request);
    }

}