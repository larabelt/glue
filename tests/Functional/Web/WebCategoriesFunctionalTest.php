<?php

use Belt\Core\Testing;
use Belt\Core\User;

class WebCategoriesFunctionalTest extends Testing\BeltTestCase
{

    public function testAsSuper()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # show
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

    }

}