<?php
namespace Belt\Glue\Behaviors;

use Belt\Glue\Category;

trait Categorizable
{

    public function categories()
    {
        return $this->morphToSortedMany(Category::class, 'categorizable');
    }

}