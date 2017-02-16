<?php

use Mockery as m;
use Belt\Core\Testing;

use Belt\Glue\Tag;
use Belt\Glue\Http\Requests\StoreTag;
use Belt\Glue\Http\Requests\PaginateTags;
use Belt\Glue\Http\Requests\UpdateTag;
use Belt\Glue\Http\Controllers\Api\TagsController;
use Belt\Core\Http\Exceptions\ApiNotFoundHttpException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagsControllerTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::__construct
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::get
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::show
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::destroy
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::update
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::store
     * @covers \Belt\Glue\Http\Controllers\Api\TagsController::index
     */
    public function test()
    {

        $this->actAsSuper();

        $tag1 = factory(Tag::class)->make();

        $qbMock = $this->getPaginateQBMock(new PaginateTags(), [$tag1]);

        $tagRepository = m::mock(Tag::class);
        $tagRepository->shouldReceive('find')->with(1)->andReturn($tag1);
        $tagRepository->shouldReceive('find')->with(999)->andReturn(null);
        $tagRepository->shouldReceive('create')->andReturn($tag1);
        $tagRepository->shouldReceive('query')->andReturn($qbMock);

        # construct
        $controller = new TagsController($tagRepository);
        $this->assertEquals($tagRepository, $controller->tags);

        # get existing tag
        $tag = $controller->get(1);
        $this->assertEquals($tag1->name, $tag->name);

        # get tag that doesn't exist
        try {
            $controller->get(999);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiNotFoundHttpException::class, $e);
        }

        # show tag
        $response = $controller->show(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = $response->getData();
        $this->assertEquals($tag1->name, $data->name);

        # destroy tag
        $response = $controller->destroy(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());

        # update tag
        $response = $controller->update(new UpdateTag(), 1);
        $this->assertInstanceOf(JsonResponse::class, $response);

        # create tag
        $response = $controller->store(new StoreTag(['name' => 'test']));
        $this->assertInstanceOf(JsonResponse::class, $response);

        # index
        $response = $controller->index(new PaginateTags());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($tag1->name, $response->getData()->data[0]->name);

    }

}