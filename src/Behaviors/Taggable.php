<?php

namespace Belt\Glue\Behaviors;

use DB;
use Belt\Glue\Tag;

trait Taggable
{

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function purgeTags()
    {
        DB::connection($this->getConnectionName())
            ->table('taggables')
            ->where('taggable_id', $this->id)
            ->where('taggable_type', $this->getMorphClass())
            ->delete();
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