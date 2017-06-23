<?php

namespace Belt\Glue\Behaviors;

use DB;
use Belt\Glue\Category;

trait Categorizable
{

    /**
     * @return \Rutorika\Sortable\BelongsToSortedMany
     */
    public function categories()
    {
        return $this->morphToSortedMany(Category::class, 'categorizable');
    }

    /**
     * Begin category query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function categoryQB()
    {
        return Category::query();
    }

    /**
     * Purge associated categories
     */
    public function purgeCategories()
    {
        DB::connection($this->getConnectionName())
            ->table('categorizables')
            ->where('categorizable_id', $this->id)
            ->where('categorizable_type', $this->getMorphClass())
            ->delete();
    }

    /**
     * Return items associated with the given category
     *
     * @param $query
     * @param $category
     * @return mixed
     */
    public function scopeHasCategory($query, $category)
    {
        $query->whereHas('categories', function ($query) use ($category) {
            $categories = is_array($category) ? $category : explode(',', $category);
            foreach ($categories as $n => $category) {
                $column = is_numeric($category) ? 'id' : 'slug';
                $method = $n === 0 ? 'where' : 'orWhere';
                $query->$method('categories.' . $column, $category);
            }
        });

        return $query;
    }

    /**
     * Return items associated with the given category
     *
     * @param $query
     * @param $category
     * @return mixed
     */
    public function scopeInCategory($query, $category)
    {
        $query->whereHas('categories', function ($query) use ($category) {
            $categories = is_array($category) ? $category : explode(',', $category);
            foreach ($categories as $n => $value) {
                $method = $n === 0 ? 'where' : 'orWhere';
                $category = $this->categoryQB()->sluggish($value)->first();
                $query->$method(function ($sub) use ($category) {
                    $sub->where('categories._lft', '>=', $category->_lft);
                    $sub->where('categories._rgt', '<=', $category->_rgt);
                });
            }
        });

        return $query;
    }

}