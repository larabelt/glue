<?php

use Belt\Glue\Http\Requests\AttachTag;

class AttachTagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Belt\Glue\Http\Requests\AttachTag::rules
     */
    public function test()
    {

        $request = new AttachTag();

        $this->assertNotEmpty($request->rules());
    }

}