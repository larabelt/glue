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
    public function modify(Builder $qb, PaginateRequest $request)
    {
        if ($tag = $request->query('tag')) {
            if ($request->query('all_tags')) {
                $qb->hasAllTags($tag);
            } else {
                $qb->hasTag($tag);
            }
        }
    }
}