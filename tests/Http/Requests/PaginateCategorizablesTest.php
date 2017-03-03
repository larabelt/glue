<?php
use Mockery as m;
use Belt\Core\Testing;

use Belt\Content\Page;
use Belt\Glue\Category;
use Belt\Glue\Http\Requests\PaginateCategorizables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PaginateCategorizablesTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Requests\PaginateCategorizables::modifyQuery
     * @covers \Belt\Glue\Http\Requests\PaginateCategorizables::items
     */
    public function test()
    {
        $page = new Page();
        $page->id = 1;
        $page->name = 'page';

        $category1 = new Category();
        $category1->id = 1;
        $category1->name = 'category 1';

        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('categoried')->once()->with('pages', 1);
        $qbMock->shouldReceive('notCategoried')->once()->with('pages', 1);
        $qbMock->shouldReceive('get')->once()->andReturn(new Collection([$category1]));

        # modifyQuery
        $paginateRequest = new PaginateCategorizables(['categorizable_id' => 1, 'categorizable_type' => 'pages']);
        $paginateRequest->modifyQuery($qbMock);
        $paginateRequest->merge(['not' => true]);
        $paginateRequest->modifyQuery($qbMock);

        # items
        $paginateRequest->items($qbMock);
    }

}