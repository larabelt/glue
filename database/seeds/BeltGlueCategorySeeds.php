<?php

use Illuminate\Database\Seeder;

use Belt\Glue\Category;

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
    }
}
