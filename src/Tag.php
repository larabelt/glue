<?php
namespace Belt\Glue;

use Belt;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package Belt\Glue
 */
class Tag extends Model implements
    Belt\Core\Behaviors\SluggableInterface,
    Belt\Content\Behaviors\IncludesContentInterface
{
    use Belt\Core\Behaviors\Sluggable;
    use Belt\Content\Behaviors\IncludesContent;

    /**
     * @var string
     */
    protected $morphClass = 'tags';

    /**
     * @var string
     */
    protected $table = 'tags';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Return tags associated with taggable
     *
     * @param $query
     * @param $taggable_type
     * @param $taggable_id
     * @return mixed
     */
    public function scopeTagged($query, $taggable_type, $taggable_id)
    {
        $query->select(['tags.*']);
        $query->join('taggables', 'taggables.tag_id', '=', 'tags.id');
        $query->where('taggables.taggable_type', $taggable_type);
        $query->where('taggables.taggable_id', $taggable_id);

        return $query;
    }

    /**
     * Return tags not associated with taggable
     *
     * @param $query
     * @param $taggable_type
     * @param $taggable_id
     * @return mixed
     */
    public function scopeNotTagged($query, $taggable_type, $taggable_id)
    {
        $query->select(['tags.*']);
        $query->leftJoin('taggables', function ($subQB) use ($taggable_type, $taggable_id) {
            $subQB->on('taggables.tag_id', '=', 'tags.id');
            $subQB->where('taggables.taggable_id', $taggable_id);
            $subQB->where('taggables.taggable_type', $taggable_type);
        });
        $query->whereNull('taggables.id');

        return $query;
    }

}