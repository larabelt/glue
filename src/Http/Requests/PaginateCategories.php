<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\PaginateRequest;

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
    ];

}