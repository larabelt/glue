<?php

use Belt\Core\Testing;
use Belt\Glue\Category;

class WebCategoriesFunctionalTest extends Testing\BeltTestCase
{

    public function testAsSuper()
    {
        $this->refreshDB();

        Category::unguard();
        $category = Category::find(1);

        # show
        $category->update(['is_active' => true]);
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

        # show (404)
        $category->update(['is_active' => false]);
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(404);

        # show (super, avoid 404)
        $this->actAsSuper();
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

    }

}