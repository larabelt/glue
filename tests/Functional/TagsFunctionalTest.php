<?php

use Belt\Core\Testing;

class TagsFunctionalTest extends Testing\BeltTestCase
{

    public function test()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # index
        $response = $this->json('GET', '/api/v1/tags');
        $response->assertStatus(200);

        # store
        $response = $this->json('POST', '/api/v1/tags', [
            'name' => 'test',
        ]);
        $response->assertStatus(201);
        $tagID = array_get($response->json(), 'id');

        # show
        $response = $this->json('GET', "/api/v1/tags/$tagID");
        $response->assertStatus(200);

        # update
        $this->json('PUT', "/api/v1/tags/$tagID", ['name' => 'updated']);
        $response = $this->json('GET', "/api/v1/tags/$tagID");
        $response->assertJson(['name' => 'updated']);

        # delete
        $response = $this->json('DELETE', "/api/v1/tags/$tagID");
        $response->assertStatus(204);
        $response = $this->json('GET', "/api/v1/tags/$tagID");
        $response->assertStatus(404);
    }

}