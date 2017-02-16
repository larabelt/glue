<?php

use Mockery as m;
use Belt\Core\Testing;
use Belt\Core\Http\Exceptions\ApiException;
use Belt\Glue\Page;
use Belt\Glue\Tag;
use Belt\Glue\Http\Requests\AttachTag;
use Belt\Glue\Http\Requests\PaginateTaggables;
use Belt\Glue\Http\Controllers\Api\TaggablesController;
use Belt\Core\Helpers\MorphHelper;

class TaggablesControllerTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::__construct
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::tag
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::taggable
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::show
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::destroy
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::store
     * @covers \Belt\Glue\Http\Controllers\Api\TaggablesController::index
     */
    public function test()
    {

        $this->actAsSuper();

        // mock page
        Page::unguard();
        $page1 = new Page();
        $page1->id = 1;
        $page1->name = 'page';

        // mock tags
        Tag::unguard();
        $tag1 = factory(Tag::class)->make();
        $tag1->id = 1;
        $tag2 = factory(Tag::class)->make();
        $tag2->id = 2;
        $page1->tags = new \Illuminate\Database\Eloquent\Collection();
        $page1->tags->add($tag1);

        // mocked dependencies
        $nullQB = $this->getQBMock();
        $nullQB->shouldReceive('first')->andReturn(null);
        $tag1QB = $this->getQBMock();
        $tag1QB->shouldReceive('first')->andReturn($tag1);
        $tag2QB = $this->getQBMock();
        $tag2QB->shouldReceive('first')->andReturn($tag2);

        $tagsQB = $this->getQBMock();
        $tagsQB->shouldReceive('where')->with('tags.id', 999)->andReturn($nullQB);
        $tagsQB->shouldReceive('where')->with('tags.id', 1)->andReturn($tag1QB);
        $tagsQB->shouldReceive('where')->with('tags.id', 2)->andReturn($tag2QB);
        $tagsQB->shouldReceive('tagged')->with('pages', 1)->andReturn($tagsQB);

        $tagRepo = m::mock(Tag::class);
        $tagRepo->shouldReceive('query')->andReturn($tagsQB);

        $morphHelper = m::mock(MorphHelper::class);
        $morphHelper->shouldReceive('morph')->with('pages', 1)->andReturn($page1);
        $morphHelper->shouldReceive('morph')->with('pages', 999)->andReturn(null);

        # construct
        $controller = new TaggablesController($tagRepo, $morphHelper);
        $this->assertEquals($tagRepo, $controller->tags);
        $this->assertEquals($morphHelper, $controller->morphHelper);

        # tag
        $tag = $controller->tag(1);
        $this->assertEquals($tag1->name, $tag->name);
        $tag = $controller->tag(1, $page1);
        $this->assertEquals($tag1->name, $tag->name);
        try {
            $controller->tag(999);
            $this->exceptionNotThrown();
        } catch (ApiException $e) {
            $this->assertEquals(404, $e->getStatusCode());
        }

        # taggable
        $page = $controller->taggable('pages', 1);
        $this->assertEquals($page1->name, $page->name);
        try {
            $controller->taggable('pages', 999);
            $this->exceptionNotThrown();
        } catch (ApiException $e) {
            $this->assertEquals(404, $e->getStatusCode());
        }

        # show
        $response = $controller->show('pages', 1, 1);
        $this->assertEquals(200, $response->getStatusCode());

        # attach tag
        $response = $controller->store(new AttachTag(['id' => 2]), 'pages', 1);
        $this->assertEquals(201, $response->getStatusCode());
        try {
            // tag already attached
            $controller->store(new AttachTag(['id' => 1]), 'pages', 1);
            $this->exceptionNotThrown();
        } catch (ApiException $e) {
            $this->assertEquals(422, $e->getStatusCode());
        }

        # detach tag
        $response = $controller->destroy('pages', 1, 1);
        $this->assertEquals(204, $response->getStatusCode());
        try {
            // tag already not attached
            $controller->destroy('pages', 1, 2);
            $this->exceptionNotThrown();
        } catch (ApiException $e) {
            $this->assertEquals(422, $e->getStatusCode());
        }

        # index
        $paginatorMock = $this->getPaginatorMock();
        $paginatorMock->shouldReceive('toArray')->andReturn([]);
        $controller = m::mock(TaggablesController::class . '[paginator]', [$tagRepo, $morphHelper]);
        $controller->shouldReceive('paginator')->andReturn($paginatorMock);
        $response = $controller->index(new PaginateTaggables(), 'pages', 1);
        $this->assertEquals(200, $response->getStatusCode());
    }

}