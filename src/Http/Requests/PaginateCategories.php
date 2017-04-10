<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Glue\Http\Requests\PaginateCategorizables;
use Belt\Glue\Http\Requests\PaginateTaggables;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateCategories
 * @package Belt\Glue\Http\Requests
 */
class PaginateCategories extends PaginateRequest
{
    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var string
     */
    public $orderBy = 'categories.id';

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
        'categories.searchable',
    ];

    public function modifyQuery(Builder $query)
    {
        if ($is_active = $this->get('is_active')) {
            $query->where('is_active', $is_active);
        }

        return $query;
    }


}