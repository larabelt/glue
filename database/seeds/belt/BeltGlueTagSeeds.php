<?php

use Illuminate\Database\Seeder;

use Belt\Glue\Tag;

class BeltGlueTagSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Tag::class, 25)->create();
    }
}
