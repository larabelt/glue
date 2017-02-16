<?php
namespace Belt\Glue\Behaviors;

use Belt\Glue\Category;
use Rutorika\Sortable\MorphToSortedManyTrait;

interface CategorizableInterface
{

    function categories();

}