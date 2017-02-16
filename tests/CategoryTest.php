<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Category::scopeCategoried
     * @covers \Belt\Glue\Category::scopeNotCategoried
     */
    public function test()
    {
        $category = factory(Category::class)->make();

        # scopeCategoried
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('select')->once()->with(['categories.*']);
        $qbMock->shouldReceive('join')->once()->with('categorizables', 'categorizables.category_id', '=', 'categories.id');
        $qbMock->shouldReceive('where')->once()->with('categorizables.categorizable_type', 'pages');
        $qbMock->shouldReceive('where')->once()->with('categorizables.categorizable_id', 1);
        $qbMock->shouldReceive('orderBy')->once()->with('categorizables.position');
        $category->scopeCategoried($qbMock, 'pages', 1);

        # scopeNotCategoried
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('select')->once()->with(['categories.*']);
        $qbMock->shouldReceive('leftJoin')->once()->with('categorizables',
            m::on(function (\Closure $closure) {
                $subQBMock = m::mock(Builder::class);
                $subQBMock->shouldReceive('on')->once()->with('categorizables.category_id', '=', 'categories.id');
                $subQBMock->shouldReceive('where')->once()->with('categorizables.categorizable_type', 'pages');
                $subQBMock->shouldReceive('where')->once()->with('categorizables.categorizable_id', 1);
                $closure($subQBMock);
                return is_callable($closure);
            })
        );
        $qbMock->shouldReceive('whereNull')->once()->with('categorizables.id');
        $category->scopeNotCategoried($qbMock, 'pages', 1);

    }

}