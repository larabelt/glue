<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Behaviors\Taggable;
use Belt\Glue\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class TaggableTest extends BeltTestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Behaviors\Taggable::tags
     * @covers \Belt\Glue\Behaviors\Taggable::purgeTags
     * @covers \Belt\Glue\Behaviors\Taggable::scopeHasTag
     */
    public function test()
    {
        # tags
        $morphMany = m::mock(Relation::class);
        $morphMany->shouldReceive('orderby')->withArgs(['delta']);
        $pageMock = m::mock(TaggableTestStub::class . '[morphMany]');
        $pageMock->shouldReceive('morphMany')->withArgs([Tag::class, 'taggable'])->andReturn($morphMany);
        $pageMock->shouldReceive('tags');
        $pageMock->tags();


        # scopeHasTag
        $taggable = new TaggableTestStub();
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('whereHas')->twice()->with('tags',
            m::on(function (\Closure $closure) {
                $qbMock = m::mock(Builder::class);
                $qbMock->shouldReceive('where')->with('tags.id', 1);
                $qbMock->shouldReceive('where')->with('tags.slug', 'test');
                $closure($qbMock);
                return is_callable($closure);
            })
        );
        $taggable->scopeHasTag($qbMock, 1);
        $taggable->scopeHasTag($qbMock, 'test');

        # purgeTags
        $taggable->id = 1;
        DB::shouldReceive('connection')->once()->andReturnSelf();
        DB::shouldReceive('table')->once()->with('taggables')->andReturnSelf();
        DB::shouldReceive('where')->once()->with('taggable_id', 1)->andReturnSelf();
        DB::shouldReceive('where')->once()->with('taggable_type', 'taggableTestStub')->andReturnSelf();
        DB::shouldReceive('delete')->once()->andReturnSelf();
        $taggable->purgeTags();
    }

}

class TaggableTestStub extends Model
{
    use Taggable;

    public function getMorphClass()
    {
        return 'taggableTestStub';
    }
}