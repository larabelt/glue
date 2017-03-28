<?php

use Mockery as m;
use Belt\Core\Testing\BeltTestCase;
use Belt\Glue\Tag;
use Illuminate\Database\Eloquent\Builder;

class TagTest extends BeltTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Belt\Glue\Tag::__toString
     * @covers \Belt\Glue\Tag::setBodyAttribute
     * @covers \Belt\Glue\Tag::scopeTagged
     * @covers \Belt\Glue\Tag::scopeNotTagged
     */
    public function test()
    {
        $tag = factory(Tag::class)->make();

        # __toString
        $tag->name = ' Test ';
        $this->assertEquals($tag->name, $tag->__toString());

        # setBodyAttribute
        $tag->body = ' Test ';
        $this->assertEquals('Test', $tag->body);

        # scopeTagged
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('select')->once()->with(['tags.*']);
        $qbMock->shouldReceive('join')->once()->with('taggables', 'taggables.tag_id', '=', 'tags.id');
        $qbMock->shouldReceive('where')->once()->with('taggables.taggable_type', 'pages');
        $qbMock->shouldReceive('where')->once()->with('taggables.taggable_id', 1);
        $tag->scopeTagged($qbMock, 'pages', 1);

        # scopeNotTagged
        $qbMock = m::mock(Builder::class);
        $qbMock->shouldReceive('select')->once()->with(['tags.*']);
        $qbMock->shouldReceive('leftJoin')->once()->with('taggables',
            m::on(function (\Closure $closure) {
                $subQBMock = m::mock(Builder::class);
                $subQBMock->shouldReceive('on')->once()->with('taggables.tag_id', '=', 'tags.id');
                $subQBMock->shouldReceive('where')->once()->with('taggables.taggable_type', 'pages');
                $subQBMock->shouldReceive('where')->once()->with('taggables.taggable_id', 1);
                $closure($subQBMock);
                return is_callable($closure);
            })
        );
        $qbMock->shouldReceive('whereNull')->once()->with('taggables.id');
        $tag->scopeNotTagged($qbMock, 'pages', 1);

    }

}