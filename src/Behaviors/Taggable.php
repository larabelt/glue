<?php
namespace Belt\Glue\Behaviors;

use Belt\Glue\Tag;

trait Taggable
{

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}