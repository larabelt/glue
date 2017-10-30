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

        $groups['filter'] = [];
        $groups['query'] = [];

        if ($value = $request->get('tag')) {
            $sets = explode(',', $value);
            foreach ($sets as $s => $set) {
                $ids = explode(' ', $set);
                foreach ($ids as $id) {

                    $filtered = substr($id, 0, 1) == '~' ? false : true;
                    $id = str_replace(['~'], '', $id);

                    $tags = $this->tags()
                        ->newQuery()
                        ->whereIn('id', [$id])
                        ->orWhereIn('slug', [$id])
                        ->get(['tags.id']);

                    $list = $tags->pluck('id')->all();

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
                    $filter['bool']['must'][] = ['terms' => ['tags' => $_group]];
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