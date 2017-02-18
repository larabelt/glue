<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\PaginateRequest;

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

}