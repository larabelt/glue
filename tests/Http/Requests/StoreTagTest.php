<?php

use Belt\Glue\Http\Requests\StoreTag;

class StoreTagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Belt\Glue\Http\Requests\StoreTag::rules
     */
    public function test()
    {

        $request = new StoreTag();

        $this->assertNotEmpty($request->rules());
    }

}