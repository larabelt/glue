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
        $this->call(BeltGluePageSeeds::class);
        $this->call(BeltGlueBlockSeeds::class);
        $this->call(BeltGlueCategorySeeds::class);
        $this->call(BeltGlueSectionSeeds::class);
        $this->call(BeltGlueTagSeeds::class);
        $this->call(BeltGlueToutSeeds::class);
    }
}
