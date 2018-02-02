<?php

use Belt\Core\Testing;
use Belt\Glue\Policies\CategoryPolicy;

class CategoryPolicyTest extends Testing\BeltTestCase
{

    use Testing\CommonMocks;

    /**
     * @covers \Belt\Glue\Policies\CategoryPolicy::view
     */
    public function test()
    {
        $user = $this->getUser();

        $policy = new CategoryPolicy();

        # view
        $this->assertTrue($policy->view($user, 1));
    }

}