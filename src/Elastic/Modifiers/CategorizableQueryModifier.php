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
        $params = $this->params($request);

        $this->filter($params);
        $this->query($params);
    }

    /**
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function find($ids)
    {
        $categories = $this->categories()
            ->newQuery()
            ->whereIn('id', $ids)
            ->orWhereIn('slug', $ids)
            ->get(['categories.id', 'categories._lft', 'categories._rgt']);

        return $categories;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function params($request)
    {
        $params['query'] = [];
        $params['filter'] = [];

        if ($value = $request->get('category')) {

            $sets = explode(',', $value);
            foreach ($sets as $n => $set) {

                $filtered = substr($set, 0, 1) == '~' ? false : true;
                $set = str_replace(['~', ' '], ['', '+'], $set);
                $ids = explode('+', $set);

                foreach ($ids as $id) {
                    $categories = $this->find([$id]);
                    $list = [];
                    foreach ($categories as $category) {
                        $list[] = $category->id;
                        $list = array_merge($list, $category->descendants->pluck('id')->all());
                    }
                    if ($list) {
                        if ($filtered) {
                            $params['filter'][$n][] = $list;
                        } else {
                            $params['query'][$n][] = $list;
                        }
                    }
                }
            }
        }

        return $params;
    }

    /**
     * @param $params
     */
    public function filter($params)
    {
        $groups = array_get($params, 'filter', []);

        if ($groups) {

            $filter = [];
            $filters = [];

            foreach ($groups as $group) {
                $filter['bool']['must'] = [];
                foreach ($group as $_group) {
                    $filter['bool']['must'][] = ['terms' => ['categories' => $_group]];
                }
                $filters[] = $filter;
            }
            if ($filters) {
                $this->engine->filter[]['bool']['should'] = $filters;
            }
        }
    }

    /**
     * @param $params
     */
    public function query($params)
    {
        $groups = array_get($params, 'query', []);

        if ($groups) {

            $query = [];
            $queries = [];

            foreach ($groups as $params) {
                $query['bool']['must'] = [];
                foreach ($params as $group) {
                    $query['bool']['must'][] = ['terms' => ['categories' => $group, 'boost' => 1]];
                }
                $queries[] = $query;
            }
            if ($queries) {
                $this->engine->query['bool']['should'][] = $queries;
            }
        }
    }
}