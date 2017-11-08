<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt\Glue\Tag;

trait TaggableModifierTrait
{

    /**
     * @var Tag
     */
    public $tags;

    /**
     * @return Tag
     */
    public function tags()
    {
        return $this->tags ?: $this->tags = new Tag();
    }

    /**
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function find($ids)
    {
        $tags = $this->tags()
            ->newQuery()
            ->whereIn('id', $ids)
            ->orWhereIn('slug', $ids)
            ->get(['tags.id']);

        return $tags;
    }
}