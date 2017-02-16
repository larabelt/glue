<?php

use Belt\Core\Testing;

class CategoriesFunctionalTest extends Testing\BeltTestCase
{

    public function test()
    {
        $this->refreshDB();
        $this->actAsSuper();

        # index
        $response = $this->json('GET', '/api/v1/categories');
        $response->assertStatus(200);

        # store
        $response = $this->json('POST', '/api/v1/categories', [
            'name' => 'test',
        ]);
        $response->assertStatus(201);
        $categoryID = array_get($response->json(), 'id');

        # show
        $response = $this->json('GET', "/api/v1/categories/$categoryID");
        $response->assertStatus(200);

        # update
        $this->json('PUT', "/api/v1/categories/$categoryID", ['name' => 'updated']);
        $response = $this->json('GET', "/api/v1/categories/$categoryID");
        $response->assertJson(['name' => 'updated']);

        # delete
        $response = $this->json('DELETE', "/api/v1/categories/$categoryID");
        $response->assertStatus(204);
        $response = $this->json('GET', "/api/v1/categories/$categoryID");
        $response->assertStatus(404);
    }

}