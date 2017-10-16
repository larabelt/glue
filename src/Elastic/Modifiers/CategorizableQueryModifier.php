<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;

class CategorizableQueryModifier extends PaginationQueryModifier
{
    /**
     * Modify the query
     *
     * @param  PaginateRequest $request
     * @return $query
     */
    public function modify(PaginateRequest $request)
    {

        $weighted = [];
        $filtered = [];

        if ($category = $request->get('category')) {
            $categories = explode(',', $category);
            $filtered = $categories;
        }

        /**
         * @todo look at needle for matching categories
         */
        /*if ($weighted) {
            $this->engine->query['bool']['should'][] = [
                'terms' => [
                    'categories' => $weighted,
                    'boost' => 1,
                ]
            ];
        }*/

        if ($filtered) {
            $this->engine->filter[]['bool']['must'][] = [
                'terms' => [
                    'categories' => $filtered
                ]
            ];
        }

    }
}