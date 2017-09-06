<?php

namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\PaginateRequest;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateTags
 * @package Belt\Glue\Http\Requests
 */
class PaginateTags extends PaginateRequest
{
    /**
     * @var int
     */
    public $perTag = 10;

    /**
     * @var string
     */
    public $orderBy = 'tags.id';

    /**
     * @var array
     */
    public $sortable = [
        'tags.id',
        'tags.name',
    ];

    /**
     * @var array
     */
    public $searchable = [
        'tags.name',
    ];

    /**
     * @inheritdoc
     */
    public function modifyQuery(Builder $query)
    {
        # show tags not in array
        if ($not = $this->get('not')) {
            $query->whereNotIn('tags.id', explode(',', $not));
        }

        # show tags in array
        if ($in = $this->get('in')) {
            $query->whereIn('tags.id', explode(',', $in));
        }

        return $query;
    }

}