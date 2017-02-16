<?php

use Belt\Core\Testing;
use Belt\Glue\Policies\TagPolicy;

class TagPolicyTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    /**
     * @covers \Belt\Glue\Policies\TagPolicy::view
     */
    public function test()
    {
        $user = $this->getUser();

        $policy = new TagPolicy();

        # view
        $this->assertTrue($policy->view($user, 1));
    }

}