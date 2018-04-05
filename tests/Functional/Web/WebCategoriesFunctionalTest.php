<?php

use Belt\Core\Testing;
use Belt\Glue\Category;

class WebCategoriesFunctionalTest extends Testing\BeltTestCase
{

    public function testAsSuper()
    {
        $this->refreshDB();
        $this->actAsSuper();

        Category::unguard();
        $category = Category::find(1);
        $category->update(['is_active' => true]);

        # show
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200);

        $category->update(['is_active' => false]);

        # show (404)
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(404);

    }

}