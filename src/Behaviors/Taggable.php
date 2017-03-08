<?php
namespace Belt\Glue\Behaviors;

use Belt\Glue\Tag;

trait Taggable
{

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Return items associated with the given tag
     *
     * @param $query
     * @param $tag
     * @return mixed
     */
    public function scopeHasTag($query, $tag)
    {
        $query->whereHas('tags', function ($subQB) use ($tag) {
            $column = is_numeric($tag) ? 'id' : 'slug';
            $subQB->where($column, $tag);
        });

        return $query;
    }

}