<?php

namespace Belt\Glue\Http\Requests;

use Belt\Glue\Tag;
use Belt\Core\Http\Requests\PaginateRequest;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginateTaggables
 * @package Belt\Glue\Http\Requests
 */
class PaginateTaggables extends PaginateTags
{
    /**
     * @var int
     */
    public $perPage = 5;
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
     * @inheritdoc
     */
    public function modifyQuery(Builder $query)
    {
        # show tags associated with taggable
        if (!$this->get('not')) {
            $query->tagged($this->get('taggable_type'), $this->get('taggable_id'));
        }

        # show tags not associated with taggable
        if ($this->get('not')) {
            $query->notTagged($this->get('taggable_type'), $this->get('taggable_id'));
        }

        return $query;
    }

    /**
     * @param PaginateRequest $request
     * @param $query
     * @return mixed
     */
    public static function scopeHasTag(PaginateRequest $request, $query)
    {
        if ($tag_id = $request->get('tag_id')) {
            $query->hasTag($tag_id);
        }

        return $query;
    }

}