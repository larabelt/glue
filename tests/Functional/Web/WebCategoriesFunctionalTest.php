<?php

use Belt\Core\Testing;
use Belt\Core\User;

class WebCategoriesFunctionalTest extends Testing\BeltTestCase
{

    public function testAsSuper()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # preview
        $response = $this->json('GET', '/categories/1/preview');
        $response->assertStatus(200);

        # show compiles b/c debug env
        putenv("APP_DEBUG=true");
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

        # show compiles b/c acting as super
        putenv("APP_DEBUG=false");
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

    }

    public function testAsLoggedInUser()
    {
        $this->refreshDB();

        # show caches b/c logged in user does not own category
        putenv("APP_DEBUG=false");
        $this->actingAs(factory(User::class)->make(['is_super' => false]));
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);
    }

}