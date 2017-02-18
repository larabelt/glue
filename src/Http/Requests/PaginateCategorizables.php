<?php
namespace Belt\Glue\Http\Requests;

use Belt\Glue\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateCategorizables
 * @package Belt\Glue\Http\Requests
 */
class PaginateCategorizables extends PaginateCategories
{
    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var string
     */
    public $orderBy = 'categories._lft';

    /**
     * @inheritdoc
     */
    public function modifyQuery(Builder $query)
    {
        # show categories associated with categorizable
        if (!$this->get('not')) {
            $query->categoried($this->get('categorizable_type'), $this->get('categorizable_id'));
        }

        # show categories not associated with categorizable
        if ($this->get('not')) {
            $query->notCategoried($this->get('categorizable_type'), $this->get('categorizable_id'));
        }

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function items(Builder $query)
    {
        return $query->get();
    }

}