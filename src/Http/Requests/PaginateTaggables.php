<?php
namespace Belt\Glue\Http\Requests;

use Belt\Glue\Tag;
use Illuminate\Database\Eloquent\Builder;

class PaginateTaggables extends PaginateTags
{
    public $perPage = 5;
    /**
     * @var Tag
     */
    public $tags;

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
     * @inheritdoc
     */
    public function items(Builder $query)
    {
        return $query->get();
    }

}