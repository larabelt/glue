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

}