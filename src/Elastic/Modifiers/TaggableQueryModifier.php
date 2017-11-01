<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;
use Belt\Glue\Tag;

class TaggableQueryModifier extends PaginationQueryModifier
{

    /**
     * @var Tag
     */
    public $tags;

    /**
     * @return Tag
     */
    public function tags()
    {
        return $this->tags ?: $this->tags = new Tag();
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
        $tags = $this->tags()
            ->newQuery()
            ->whereIn('id', $ids)
            ->orWhereIn('slug', $ids)
            ->get(['tags.id']);

        return $tags;
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
        $filter = [];
        $filters = [];
        if ($params['filter']) {
            foreach ($params['filter'] as $s => $group) {
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
        $query = [];
        $queries = [];
        if ($params['query']) {
            foreach ($params['query'] as $s => $params) {
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