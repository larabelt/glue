<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Behaviors\Categorizable;
use Belt\Glue\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class CategorizableTest extends BeltTestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Behaviors\Categorizable::categories
     * @covers \Belt\Glue\Behaviors\Categorizable::categoryQB
     * @covers \Belt\Glue\Behaviors\Categorizable::purgeCategories
     * @covers \Belt\Glue\Behaviors\Categorizable::scopeHasCategory
     * @covers \Belt\Glue\Behaviors\Categorizable::scopeInCategory
     */
    public function test()
    {
        # categories
        $morphMany = m::mock(Relation::class);
        $morphMany->shouldReceive('orderby')->withArgs(['delta']);
        $pageMock = m::mock(CategorizableTestStub::class . '[morphMany]');
        $pageMock->shouldReceive('morphToSortedMany')->withArgs([Category::class, 'categorizable'])->andReturn($morphMany);
        $pageMock->shouldReceive('categories');
        $pageMock->categories();

        # purgeCategories
        $categorizable = new CategorizableTestStub();
        $categorizable->id = 1;
        DB::shouldReceive('connection')->once()->andReturnSelf();
        DB::shouldReceive('table')->once()->with('categorizables')->andReturnSelf();
        DB::shouldReceive('where')->once()->with('categorizable_id', 1)->andReturnSelf();
        DB::shouldReceive('where')->once()->with('categorizable_type', 'categorizableTestStub')->andReturnSelf();
        DB::shouldReceive('delete')->once()->andReturnSelf();
        $categorizable->purgeCategories();

        # scopeHasCategory
        $categorizable = new CategorizableTestStub();
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('whereHas')->twice()->with('categories',
            m::on(function (\Closure $closure) {
                $qbMock = m::mock(Builder::class);
                $qbMock->shouldReceive('where')->with('categories.id', 1);
                $qbMock->shouldReceive('where')->with('categories.slug', 'test');
                $closure($qbMock);
                return is_callable($closure);
            })
        );
        $categorizable->scopeHasCategory($qbMock, 1);
        $categorizable->scopeHasCategory($qbMock, 'test');

        # scopeInCategory
        $categorizable = new CategorizableTestStub();
        $qb = m::mock(Builder::class);
        $qb->shouldReceive('whereHas')->twice()->with('categories',
            m::on(function (\Closure $closure) {
                $qb = m::mock(Builder::class);
                $qb->shouldReceive('where')->with(
                    m::on(function (\Closure $closure) {
                        $sub = m::mock(Builder::class);
                        $sub->shouldReceive('where')->with('categories._lft', '>=', 10)->andReturnSelf();
                        $sub->shouldReceive('where')->with('categories._rgt', '<=', 20)->andReturnSelf();
                        $closure($sub);
                        return is_callable($closure);
                    })
                );
                $closure($qb);
                return is_callable($closure);
            })
        );
        $categorizable->scopeInCategory($qb, 1);
        $categorizable->scopeInCategory($qb, 'test');

        # categoryQB
        $categorizable = new CategorizableTestStub2();
        $this->assertInstanceOf(Builder::class, $categorizable->categoryQB());
    }

}

class CategorizableTestStub extends Model
{
    use Categorizable;

    public function getMorphClass()
    {
        return 'categorizableTestStub';
    }

    public function categoryQB()
    {
        Category::unguard();

        $qb = m::mock(Builder::class);
        $qb->shouldReceive('sluggish')->with(1)->andReturnSelf();
        $qb->shouldReceive('sluggish')->with('test')->andReturnSelf();
        $qb->shouldReceive('first')->andReturn(
            new Category([
                '_lft' => 10,
                '_rgt' => 20,
            ])
        );

        return $qb;
    }
}

class CategorizableTestStub2 extends Model
{
    use Categorizable;
}