<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Category::scopeCategoried
     * @covers \Belt\Glue\Category::scopeNotCategoried
     * @covers \Belt\Glue\Category::getHierarchyAttribute
     * @covers \Belt\Glue\Category::getFullNameAttribute
     * @covers \Belt\Glue\Category::getDefaultUrlAttribute
     * @covers \Belt\Glue\Category::getUrlAttribute
     * @covers \Belt\Glue\Category::pages
     */
    public function test()
    {
        $category = factory(Category::class)->make();

        # getFullNameAttribute
        $this->assertEquals($category->getNestedName(), $category->full_name);

        # getDefaultUrlAttribute
        $this->assertNotEmpty($category->default_url);
        $this->assertEquals($category->default_url, $category->url);

        # pages (bletch)
        $this->assertInstanceOf(Collection::class, $category->pages);

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

        # getHierarchyAttribute
        Category::unguard();
        $ancestors = new \Illuminate\Database\Eloquent\Collection();
        $ancestors->push(new Category([
            'id' => 1,
            'name' => 'One',
            'slug' => 'one',
        ]));
        $ancestors->push(new Category([
            'id' => 2,
            'name' => 'Two',
            'slug' => 'two',
        ]));
        $ancestors->push(new Category([
            'id' => 3,
            'name' => 'Three',
            'slug' => 'three',
        ]));
        $category = m::mock(Category::class . '[ancestors]');
        $category->shouldReceive('ancestors')->andReturnSelf();
        $category->shouldReceive('get')->andReturn($ancestors);
        $category->id = 4;
        $category->name = 'Four';
        $category->slug = 'four';
        $hierarchy = $category->getHierarchyAttribute();
        $this->assertEquals(4, count($hierarchy));
        $this->assertEquals('four', array_get($hierarchy, '3.slug'));

    }

}