<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Belt\Glue\Category;
use Belt\Glue\Observers\CategoryObserver;

class BeltUpdateCategoriesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('names')->nullable();
            $table->text('slugs')->nullable();
        });

        CategoryObserver::$touch = true;

        $categories = Category::query()
            ->whereNull('parent_id')
            ->get();

        foreach ($categories as $category) {
            $category->touch();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('names');
            $table->dropColumn('slugs');
        });
    }
}