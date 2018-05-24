<?php

use Belt\Glue\Http\Requests\UpdateTag;

class UpdateTagTest extends \PHPUnit\Framework\TestCase
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