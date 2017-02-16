<?php

use Belt\Core\Testing;

class TaggablesFunctionalTest extends Testing\BeltTestCase
{

    public function test()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # index
        $response = $this->json('GET', '/api/v1/taggables/pages/1');
        $response->assertStatus(200);

        # attach
        $response = $this->json('POST', '/api/v1/taggables/pages/1', [
            'id' => 1
        ]);
        $response->assertStatus(201);
        $response = $this->json('GET', "/api/v1/taggables/pages/1/1");
        $response->assertStatus(200);

        # show
        $response = $this->json('GET', "/api/v1/taggables/pages/1/1");
        $response->assertStatus(200);

        # detach
        $response = $this->json('DELETE', "/api/v1/taggables/pages/1/1");
        $response->assertStatus(204);
        $response = $this->json('GET', "/api/v1/taggables/pages/1/1");
        $response->assertStatus(404);
    }

}