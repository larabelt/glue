<?php
namespace Belt\Glue\Behaviors;

use DB;
use Belt\Glue\Category;

trait Categorizable
{

    public function categories()
    {
        return $this->morphToSortedMany(Category::class, 'categorizable');
    }

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

}