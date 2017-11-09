<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Section;
use Belt\Glue\Category;
use Belt\Glue\Jobs\UpdateCategoryData;
use Belt\Glue\Observers\CategoryObserver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class CategoryObserverTest extends BeltTestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Observers\CategoryObserver::deleting
     * @covers \Belt\Glue\Observers\CategoryObserver::saving
     * @covers \Belt\Glue\Observers\CategoryObserver::saved
     * @covers \Belt\Glue\Observers\CategoryObserver::readyDispatch
     * @covers \Belt\Glue\Observers\CategoryObserver::dispatch
     */
    public function test()
    {
        Category::unguard();
        $observer = new CategoryObserver();

        # deleting
        $section = m::mock(Section::class);
        $section->shouldReceive('delete')->once();
        $category = m::mock(Category::class . '[attachments]');
        $category->shouldReceive('attachments')->once()->andReturnSelf();
        $category->shouldReceive('detach')->once()->andReturnSelf();
        $category->id = 1;
        $category->sections = new Collection([$section]);
        DB::shouldReceive('table')->once()->andReturnSelf();
        DB::shouldReceive('where')->once()->with('category_id', 1)->andReturnSelf();
        DB::shouldReceive('delete')->once();
        $observer->deleting($category);

        # saving, saved, readyDispatch, dispatch
        Queue::fake();
        $category = m::mock(Category::class);
        $category->shouldReceive('getDirty')->andReturn(['name' => 'new name']);
        $observer = new CategoryObserver();
        $observer->saving($category);
        $observer->saved($category);
        Queue::assertPushed(UpdateCategoryData::class, function ($job) use ($category) {
            return $job->category === $category;
        });

    }

}