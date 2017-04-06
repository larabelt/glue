<?php

namespace Belt\Glue\Observers;

use Belt\Glue\Category;

class CategoryObserver
{
    /**
     * Listen to the Category deleting event.
     *
     * @param  Category  $category
     * @return void
     */
    public function deleting(Category $category)
    {
        $category->attachments()->detach();

        foreach($category->sections as $section) {
            $section->delete();
        }
    }
}