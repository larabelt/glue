<?php

namespace Belt\Glue\Observers;

use Belt\Glue\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{

    public static $touch = false;

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
        if (!static::$touch && (isset($dirty['name']) || isset($dirty['slug']))) {
            static::$touch = true;
        }

        $category->names = $category->getNestedNames();
        $category->slugs = $category->getNestedSlugs();
    }

    /**
     * Listen to the Category saving event.
     *
     * @param  Category $category
     * @return void
     */
    public function saved(Category $category)
    {
        if (static::$touch) {
            foreach ($category->children as $child) {
                $child->touch();
            }
        }
    }

}