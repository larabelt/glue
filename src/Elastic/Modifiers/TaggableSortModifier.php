<?php

namespace Belt\Glue\Elastic\Modifiers;

use Belt;
use Belt\Core\Helpers;
use Belt\Core\Http\Requests\PaginateRequest;
use Belt\Content\Elastic\Modifiers\PaginationQueryModifier;
use Belt\Glue\Tag;

class TaggableSortModifier extends PaginationQueryModifier
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

        if ($orderBy = $request->get('orderBy')) {
            $sortHelper = new Helpers\SortHelper($orderBy);
            if ($sort = $sortHelper->getByColumn('tag')) {
                $tags = $this->find($sort->params);
                if ($tags) {
                    $this->engine->sort['_script'] = [
                        'type' => 'number',
                        'order' => 'desc',
                        'script' => [
                            'lang' => 'painless',
                            'inline' => "
                                int tag;
                                int count = 0; 
                                for(int i=0; i < doc['tags'].length; i++) { 
                                    tag = (int) (long) doc['tags'][i];
                                    if (params.tags.indexOf(tag) > -1) {
                                        count = count + 1;
                                    } 
                                } 
                                return count;
                            ",
                            'params' => [
                                'tags' => $tags->pluck('id')->all(),
                            ],
                        ]
                    ];
                }
            }
        }
    }
}