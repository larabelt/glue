<?php

use Belt\Glue\Http\Requests\UpdateTag;

class UpdateTagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Belt\Glue\Http\Requests\UpdateTag::rules
     */
    public function test()
    {

        $request = new UpdateTag();

        $this->assertNotEmpty($request->rules());
    }

}