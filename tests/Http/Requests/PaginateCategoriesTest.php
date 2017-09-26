<?php
use Mockery as m;
use Belt\Core\Testing;

use Belt\Content\Page;
use Belt\Glue\Category;
use Belt\Glue\Http\Requests\PaginateCategories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PaginateCategoriesTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Requests\PaginateCategories::modifyQuery
     */
    public function test()
    {
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('whereIn')->once()->with('categories.parent_id', [1]);

        # modifyQuery
        $paginateRequest = new PaginateCategories(['parent_id' => 1]);
        $paginateRequest->modifyQuery($qbMock);
    }

}