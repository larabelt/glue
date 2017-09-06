<?php

use Mockery as m;
use Belt\Core\Testing;
use Belt\Glue\Http\Requests\PaginateTags;
use Illuminate\Database\Eloquent\Builder;

class PaginateTagsTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Requests\PaginateTags::modifyQuery
     */
    public function test()
    {
        # modifyQuery
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('whereNotIn')->once()->with('tags.id', [1]);
        $qb->shouldReceive('whereIn')->once()->with('tags.id', [3,4]);

        $paginateRequest = new PaginateTags(['not' => 1, 'in' => '3,4']);
        $paginateRequest->modifyQuery($qb);

    }

}