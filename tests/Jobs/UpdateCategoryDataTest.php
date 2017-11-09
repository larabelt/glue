<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Category;
use Belt\Glue\Jobs\UpdateCategoryData;
use Illuminate\Database\Eloquent\Collection;

class UpdateCategoryDataTest extends BeltTestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Jobs\UpdateCategoryData::__construct
     * @covers \Belt\Glue\Jobs\UpdateCategoryData::handle
     * @covers \Belt\Glue\Jobs\UpdateCategoryData::__handle
     */
    public function test()
    {
        $category1 = new UpdateCategoryDataTestStub();
        $category1->children = new Collection([new UpdateCategoryDataTestStub()]);

        $job = new UpdateCategoryData($category1);
        $job->handle();
    }

}

class UpdateCategoryDataTestStub extends Category
{

    public function getNestedNames()
    {
        return ['name'];
    }

    public function getNestedSlugs()
    {
        return ['name'];
    }

    public function save(array $options = [])
    {

    }

}