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

        # hasTag
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('hasTag')->once()->with(1);
        $request = new PaginateRequest(['tag' => '1']);
        $modifer = new TaggableQueryModifier($qb, $request);
        $modifer->modify($qb, $request);

        # all_tags = true
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('hasAllTags')->once()->with('1,2');
        $request = new PaginateRequest(['tag' => '1,2', 'all_tags' => 1]);
        $modifer = new TaggableQueryModifier($qb, $request);
        $modifer->modify($qb, $request);
    }

}