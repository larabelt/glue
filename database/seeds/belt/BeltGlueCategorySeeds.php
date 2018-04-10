<?php

use Belt\Content\Section;
use Belt\Glue\Category;
use Illuminate\Database\Seeder;

class BeltGlueCategorySeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 10)
            ->create()
            ->each(function ($c1) {
                factory(Category::class, random_int(3, 5))
                    ->create(['parent_id' => $c1->id])
                    ->each(function ($c2) {
                        $count = random_int(0, 3);
                        if ($count) {
                            factory(Category::class, random_int(3, 5))
                                ->create(['parent_id' => $c2->id]);
                        }
                    });
            });

        $faker = \Faker\Factory::create();

        # make sectioned example category
        $category = Category::first();
        Section::where('owner_id', $category->id)->where('owner_type', 'categories')->delete();
        $category->update([
            'template' => 'default',
            'slug' => 'sectioned',
            'body' => null
        ]);

        # section
        $this->section($category, 'sections', [
            'before' => $faker->paragraphs(3, true),
        ]);

        $this->section($category, 'sections', [
            'before' => $faker->paragraphs(3, true),
        ]);

        $this->section($category, 'sections', [
            'before' => $faker->paragraphs(3, true),
        ]);
    }

    public function section($owner, $sectionable = 'sections', $options = [], $params = [])
    {

        $category = $owner instanceof Category ? $owner : null;

        $parent = $owner instanceof Section ? $owner : null;

        $sectionable_id = null;
        $sectionable_type = $sectionable;
        if ($sectionable && is_object($sectionable)) {
            $sectionable_id = $sectionable->id;
            $sectionable_type = $sectionable->getMorphClass();
        }

        $section = factory(Section::class)->create([
            'template' => array_get($options, 'template', 'default'),
            'parent_id' => $parent ? $parent->id : null,
            'owner_id' => $category ? $category->id : $parent->owner_id,
            'owner_type' => 'categories',
            'sectionable_id' => $sectionable_id,
            'sectionable_type' => $sectionable_type,
            'heading' => array_get($options, 'heading', null),
            'before' => array_get($options, 'before', null),
            'after' => array_get($options, 'after', null),
        ]);

        foreach ($params as $key => $value) {
            $section->saveParam($key, $value);
        }

        return $section;
    }
}
