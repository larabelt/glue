<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\PaginateRequest;

class PaginateCategories extends PaginateRequest
{
    public $perPage = 10;

    public $orderBy = 'categories.id';

    public $sortable = [
        'categories.id',
        'categories.name',
    ];

    public $searchable = [
        'categories.name',
    ];

}