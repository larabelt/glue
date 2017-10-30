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

        $groups['query'] = [];
        $groups['filter'] = [];

        if ($value = $request->get('category')) {
            $sets = explode(',', $value);
            foreach ($sets as $s => $set) {
                $ids = explode(' ', $set);
                foreach ($ids as $id) {

                    $filtered = substr($id, 0, 1) == '~' ? false : true;
                    $id = str_replace(['~'], '', $id);

                    $categories = $this->categories()
                        ->newQuery()
                        ->whereIn('id', [$id])
                        ->orWhereIn('slug', [$id])
                        ->get(['categories.id', 'categories._lft', 'categories._rgt']);
                    $list = [];
                    foreach ($categories as $category) {
                        $list = [$category->id];
                        $list = array_merge($list, $category->descendants()->pluck('id')->all());
                    }
                    if ($list) {
                        if ($filtered) {
                            $groups['filter'][$s][] = $list;
                        } else {
                            $groups['query'][$s][] = $list;
                        }
                    }
                }
            }
        }

        $filter = [];
        $filters = [];
        if ($groups['filter']) {
            foreach ($groups['filter'] as $s => $group) {
                $filter['bool']['must'] = [];
                foreach ($group as $_group) {
                    $filter['bool']['must'][] = ['terms' => ['categories' => $_group]];
                }
                $filters[$s] = $filter;
            }
            if ($filters) {
                $this->engine->filter[]['bool']['should'] = $filters;
            }
        }

        $query = [];
        $queries = [];

        if ($groups['query']) {
            foreach ($groups['query'] as $s => $groups) {
                $query['bool']['must'] = [];
                foreach ($groups as $group) {
                    $query['bool']['must'][] = ['terms' => ['categories' => $group, 'boost' => 1]];
                }
                $queries[$s] = $query;
            }
            if ($queries) {
                $this->engine->query['bool']['should'][] = $queries;
            }
        }


    }
}