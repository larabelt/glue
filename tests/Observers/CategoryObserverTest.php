<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Content\Section;
use Belt\Glue\Category;
use Belt\Glue\Observers\CategoryObserver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryObserverTest extends BeltTestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Observers\CategoryObserver::deleting
     */
    public function test()
    {
        $observer = new CategoryObserver();

        $section = m::mock(Section::class);
        $section->shouldReceive('delete')->once();

        Category::unguard();
        $category = m::mock(Category::class . '[attachments]');
        $category->shouldReceive('attachments')->once()->andReturnSelf();
        $category->shouldReceive('detach')->once()->andReturnSelf();
        $category->id = 1;
        $category->sections = new Collection([$section]);

        DB::shouldReceive('table')->once()->andReturnSelf();
        DB::shouldReceive('where')->once()->with('category_id', 1)->andReturnSelf();
        DB::shouldReceive('delete')->once();

        $observer->deleting($category);

    }

}