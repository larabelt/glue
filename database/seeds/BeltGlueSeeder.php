<?php

use Illuminate\Database\Seeder;

class BeltGlueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BeltGlueCategorySeeds::class);
        $this->call(BeltGlueTagSeeds::class);
    }
}
