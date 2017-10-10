<?php

namespace Belt\Glue;

use Belt;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package Belt\Glue
 */
class Category extends Model implements
    Belt\Core\Behaviors\IsNestedInterface,
    Belt\Core\Behaviors\IsSearchableInterface,
    Belt\Core\Behaviors\ParamableInterface,
    Belt\Core\Behaviors\SluggableInterface,
    Belt\Core\Behaviors\TypeInterface,
    Belt\Clip\Behaviors\ClippableInterface,
    Belt\Content\Behaviors\HasSectionsInterface,
    Belt\Content\Behaviors\IncludesContentInterface,
    Belt\Content\Behaviors\IncludesTemplateInterface
{

    use Belt\Core\Behaviors\IsNested;
    use Belt\Core\Behaviors\IsSearchable;
    use Belt\Core\Behaviors\HasSortableTrait;
    use Belt\Core\Behaviors\Sluggable;
    use Belt\Core\Behaviors\TypeTrait;
    use Belt\Clip\Behaviors\Clippable;
    use Belt\Content\Behaviors\HasSections;
    use Belt\Content\Behaviors\IncludesContent;
    use Belt\Content\Behaviors\IncludesTemplate;

    /**
     * @var string
     */
    protected $morphClass = 'categories';

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $fillable = ['name', 'body'];

    /**
     * @var array
     */
    protected $appends = ['full_name', 'default_url', 'url', 'hierarchy', 'image', 'morph_class'];

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->getNestedName();
    }

    /**
     * Return categories associated with categorizable
     *
     * @param $query
     * @param $categorizable_type
     * @param $categorizable_id
     * @return mixed
     */
    public function scopeCategoried($query, $categorizable_type, $categorizable_id)
    {
        $query->select(['categories.*']);
        $query->join('categorizables', 'categorizables.category_id', '=', 'categories.id');
        $query->where('categorizables.categorizable_type', $categorizable_type);
        $query->where('categorizables.categorizable_id', $categorizable_id);
        $query->orderBy('categorizables.position');

        return $query;
    }

    /**
     * Return categories not associated with categorizable
     *
     * @param $query
     * @param $categorizable_type
     * @param $categorizable_id
     * @return mixed
     */
    public function scopeNotCategoried($query, $categorizable_type, $categorizable_id)
    {
        $query->select(['categories.*']);
        $query->leftJoin('categorizables', function ($subQB) use ($categorizable_type, $categorizable_id) {
            $subQB->on('categorizables.category_id', '=', 'categories.id');
            $subQB->where('categorizables.categorizable_id', $categorizable_id);
            $subQB->where('categorizables.categorizable_type', $categorizable_type);
        });
        $query->whereNull('categorizables.id');

        return $query;
    }

    /**
     * @return string
     */
    public function getDefaultUrlAttribute()
    {
        $url = ['categories'];

        foreach ($this->hierarchy as $item) {
            $url[] = $item['slug'];
        }

        return '/' . implode('/', $url);
    }

    /**
     * @deprecated
     */
    public function getUrlAttribute()
    {
        $this->getDefaultUrlAttribute();
    }

    /**
     * Get all of the pages that are assigned this category.
     *
     * @deprecated
     */
    public function pages()
    {
        return $this->morphedByMany(Belt\Content\Page::class, 'categorizable');
    }

}