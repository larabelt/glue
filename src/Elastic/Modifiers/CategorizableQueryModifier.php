<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;
use Belt\Glue\Category;

class CategorizableQueryModifier extends PaginationQueryModifier
{


    /**
     * @var Category
     */
    public $categories;

    /**
     * @return Category
     */
    public function categories()
    {
        return $this->categories ?: $this->categories = new Category();
    }

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

        if ($id = $request->get('category')) {

            $ids = explode(',', $id);
            $categories = $this->categories()
                ->newQuery()
                ->whereIn('id', $ids)
                ->orWhereIn('slug', $ids)
                ->get(['categories.id', 'categories._lft', 'categories._rgt']);

            foreach ($categories as $category) {
                $_filtered = [$category->id];
                $_filtered = array_merge($_filtered, $category->descendants()->pluck('id')->all());
                $filtered = array_unique(array_merge($filtered, $_filtered));
            }

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