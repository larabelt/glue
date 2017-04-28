<?php

namespace Belt\Glue\Behaviors;

use DB;
use Belt\Glue\Tag;

trait Taggable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     *
     */
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
        $query->whereHas('tags', function ($query) use ($tag) {
            $tags = is_array($tag) ? $tag : explode(',', $tag);
            foreach ($tags as $n => $tag) {
                $column = is_numeric($tag) ? 'id' : 'slug';
                $method = $n === 0 ? 'where' : 'orWhere';
                $query->$method('tags.' . $column, $tag);
            }
        });

        return $query;
    }

    /**
     * Return items associated with the given tag
     *
     * @param $query
     * @param $tags
     * @return mixed
     */
    public function scopeHasAllTags($query, $tags)
    {
        $tags = is_array($tags) ? $tags : explode(',', $tags);

        foreach ($tags as $tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                $column = is_numeric($tag) ? 'id' : 'slug';
                $query->where('tags.' . $column, $tag);
            });
        }

        return $query;
    }

}