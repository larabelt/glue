<?php

namespace Belt\Glue\Jobs;

use Belt;
use Belt\Glue\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateCategoryData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Category
     */
    public $category;

    /**
     * Create a new job instance.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->__handle($this->category);
    }

    /**
     * @param Category $category
     */
    public function __handle(Category $category)
    {
        $category->names = $category->getNestedNames();
        $category->slugs = $category->getNestedSlugs();
        $category->save();

        foreach ($category->children as $child) {
            $this->__handle($child);
        }
    }
}
