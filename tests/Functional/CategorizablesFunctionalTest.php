<?php

use Belt\Core\Testing;

class CategorizablesFunctionalTest extends Testing\BeltTestCase
{

    public function test()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # index
        $response = $this->json('GET', '/api/v1/pages/1/categories');
        $response->assertStatus(200);

        # attach
        $response = $this->json('POST', '/api/v1/pages/1/categories', [
            'id' => 1
        ]);
        $response->assertStatus(201);
        $response = $this->json('GET', "/api/v1/pages/1/categories/1");
        $response->assertStatus(200);
        $response = $this->json('POST', '/api/v1/pages/1/categories', [
            'id' => 1
        ]);
        $response->assertStatus(422);

        # show
        $response = $this->json('GET', "/api/v1/pages/1/categories/1");
        $response->assertStatus(200);

        # detach
        $response = $this->json('DELETE', "/api/v1/pages/1/categories/1");
        $response->assertStatus(204);
        $response = $this->json('GET', "/api/v1/pages/1/categories/1");
        $response->assertStatus(404);
    }

}