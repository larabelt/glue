<?php

namespace Belt\Glue;

use Belt;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Category
 * @package Belt\Glue
 */
class Category extends Model implements
    Belt\Clip\Behaviors\ClippableInterface,
    Belt\Core\Behaviors\SluggableInterface,
    Belt\Core\Behaviors\TypeInterface,
    Belt\Content\Behaviors\HasSectionsInterface,
    Belt\Content\Behaviors\IncludesContentInterface,
    Belt\Content\Behaviors\IncludesTemplateInterface
{

    use NodeTrait;
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
     * @param string $glue
     * @return string
     */
    public function getFullName($glue = ' > ')
    {
        $names = $this->getAncestors()->pluck('name')->all();
        $names[] = $this->name;

        return implode($glue, $names);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->getFullName();
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
     * @todo deprecate
     */
    public function getUrlAttribute()
    {
        $this->getDefaultUrlAttribute();
    }

    /**
     * @return array
     */
    public function getHierarchyAttribute()
    {
        $hierarchy = [];

        $ancestors = $this->ancestors()->get();

        if ($ancestors->count()) {
            foreach ($ancestors as $ancestor) {
                $hierarchy[] = [
                    'id' => $ancestor->id,
                    'name' => $ancestor->name,
                    'slug' => $ancestor->slug,
                ];
            }
        }

        $hierarchy[] = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];

        return $hierarchy;
    }

    /**
     * Get all of the pages that are assigned this category.
     *
     * @todo deprecate
     */
    public function pages()
    {
        return $this->morphedByMany(Belt\Content\Page::class, 'categorizable');
    }

}