<?php

namespace Belt\Glue\Http\Requests;

use Belt;
use Belt\Core\Http\Requests\PaginateRequest;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateCategories
 * @package Belt\Glue\Http\Requests
 */
class PaginateCategories extends PaginateRequest
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $modelClass = Belt\Glue\Category::class;

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var string
     */
    public $orderBy = 'categories._lft';

    /**
     * @var array
     */
    public $sortable = [
        'categories.id',
        'categories.name',
    ];

    /**
     * @var array
     */
    public $searchable = [
        'categories.name',
    ];

    /**
     * @var Belt\Core\Pagination\PaginationQueryModifier[]
     */
    public $queryModifiers = [
        Belt\Core\Pagination\InQueryModifier::class,
        Belt\Core\Pagination\IsActiveQueryModifier::class,
    ];

    /**
     * @inheritdoc
     */
    public function modifyQuery(Builder $query)
    {
        # show child categories
        if ($parent_ids = $this->get('parent_id')) {
            $query->whereIn('categories.parent_id', explode(',', $parent_ids));
        }

        return $query;
    }

}