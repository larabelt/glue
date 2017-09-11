<?php
namespace Belt\Glue\Http\Requests;

use Belt;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateCategorizables
 * @package Belt\Glue\Http\Requests
 */
class PaginateCategorizables extends PaginateCategories
{
    /**
     * @var string
     */
    public $table = 'categories';

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var string
     */
    public $orderBy = 'categories._lft';

    /**
     * @var Belt\Core\Pagination\PaginationQueryModifier[]
     */
    public $queryModifiers = [];

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

}