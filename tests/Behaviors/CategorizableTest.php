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
     * @covers \Belt\Glue\Behaviors\Categorizable::purgeCategories
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
    }

}

class CategorizableTestStub extends Model
{
    use Categorizable;

    public function getMorphClass()
    {
        return 'categorizableTestStub';
    }
}