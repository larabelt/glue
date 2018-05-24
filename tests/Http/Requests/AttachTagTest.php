<?php

use Belt\Glue\Http\Requests\AttachTag;

class AttachTagTest extends \PHPUnit\Framework\TestCase
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