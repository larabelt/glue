<?php

namespace Belt\Glue\Observers;

use Belt;
use Belt\Glue\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{

    public static $dispatch = false;

    /**
     * Listen to the Category deleting event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleting(Category $category)
    {
        $category->attachments()->detach();

        foreach ($category->sections as $section) {
            $section->delete();
        }

        DB::table('categorizables')->where('category_id', $category->id)->delete();
    }

    /**
     * Listen to the Category saving event.
     *
     * @param  Category $category
     * @return void
     */
    public function saving(Category $category)
    {
        $dirty = $category->getDirty();
        if (isset($dirty['name']) || isset($dirty['slug'])) {
            static::$dispatch = true;
        }
    }

    /**
     * Listen to the Category saving event.
     *
     * @param  Category $category
     * @return void
     */
    public function saved(Category $category)
    {
        if (static::$dispatch) {
            dispatch(new Belt\Glue\Jobs\UpdateCategoryData($category));
        }
    }

}