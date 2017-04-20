<?php

namespace Belt\Glue\Pagination;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Core\Pagination\PaginationQueryModifier;
use Illuminate\Database\Eloquent\Builder;

class TaggableQueryModifier extends PaginationQueryModifier
{
    /**
     * Modify the query
     *
     * @param  Builder $qb
     * @param  PaginateRequest $request
     * @return void
     */
    public static function modify(Builder $qb, PaginateRequest $request)
    {
        if ($tag = $request->get('tag')) {
            $qb->hasTag($tag);
        }
    }
}