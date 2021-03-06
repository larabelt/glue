<?php
use Mockery as m;
use Belt\Core\Testing;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Page;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests\PaginateTaggables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PaginateTaggablesTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Requests\PaginateTaggables::modifyQuery
     * @covers \Belt\Glue\Http\Requests\PaginateTaggables::tags
     * @covers \Belt\Glue\Http\Requests\PaginateTaggables::scopeHasTag
     */
    public function test()
    {
        $page = new Page();
        $page->id = 1;
        $page->name = 'page';

        $tag1 = new Tag();
        $tag1->id = 1;
        $tag1->name = 'tag 1';

        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('tagged')->once()->with('pages', 1);
        $qbMock->shouldReceive('notTagged')->once()->with('pages', 1);

        # modifyQuery
        $paginateRequest = new PaginateTaggables(['taggable_id' => 1, 'taggable_type' => 'pages']);
        $paginateRequest->modifyQuery($qbMock);
        $paginateRequest->merge(['not' => true]);
        $paginateRequest->modifyQuery($qbMock);

        # tags
        $this->assertNull($paginateRequest->tags);
        $paginateRequest->tags();
        $this->assertInstanceOf(Tag::class, $paginateRequest->tags);

        # scopeHasTag
        $query = m::mock(Builder::class);
        $query->shouldReceive('hasTag')->once()->with(1);
        $request = m::mock(PaginateRequest::class);
        $request->shouldReceive('get')->once()->with('tag_id')->andReturn(1);
        $paginateRequest->scopeHasTag($request, $query);
    }

}