<?php
namespace Belt\Glue\Behaviors;

use Belt\Glue\Category;
use Rutorika\Sortable\MorphToSortedManyTrait;

trait Categorizable
{

    use MorphToSortedManyTrait;

    /**
     * @todo deprecate
     *
     * Eloquent renamed getBelongsToManyCaller to guessBelongsToManyRelation
     * and the package Rutorika\Sortable currently expects the old name to exist
     *
     * @return mixed
     */
    protected function getBelongsToManyCaller()
    {
        return $this->guessBelongsToManyRelation();
    }

    public function categories()
    {
        return $this->morphToSortedMany(Category::class, 'categorizable');
    }

}