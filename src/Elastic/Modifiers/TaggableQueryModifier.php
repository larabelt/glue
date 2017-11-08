<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;
use Belt\Glue\Tag;

class TaggableQueryModifier extends PaginationQueryModifier
{

    use Belt\Glue\Elastic\Modifiers\TaggableModifierTrait;

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
     * @param $request
     * @return mixed
     */
    public function params($request)
    {
        $params['filter'] = [];
        $params['query'] = [];

        if ($value = $request->get('tag')) {
            $sets = explode(',', $value);
            foreach ($sets as $s => $set) {

                $filtered = substr($set, 0, 1) == '~' ? false : true;
                $set = str_replace(['~', ' '], ['', '+'], $set);
                $ids = explode('+', $set);

                foreach ($ids as $id) {
                    $tags = $this->find([$id]);

                    $list = $tags->pluck('id')->all();
                    if ($list) {
                        if ($filtered) {
                            $params['filter'][$s][] = $list;
                        } else {
                            $params['query'][$s][] = $list;
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

            foreach ($groups as $s => $group) {
                $filter['bool']['must'] = [];
                foreach ($group as $_group) {
                    $filter['bool']['must'][] = ['terms' => ['tags' => $_group]];
                }
                $filters[$s] = $filter;
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

            foreach ($groups as $s => $params) {
                $query['bool']['must'] = [];
                foreach ($params as $group) {
                    $query['bool']['must'][] = ['terms' => ['tags' => $group, 'boost' => 1]];
                }
                $queries[$s] = $query;
            }
            if ($queries) {
                $this->engine->query['bool']['should'][] = $queries;
            }
        }
    }
}