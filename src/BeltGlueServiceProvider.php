<?php

namespace Belt\Glue;

use Belt, Validator, View;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class BeltGlueServiceProvider
 * @package Belt\Glue
 */
class BeltGlueServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Belt\Glue\Category::class => Belt\Glue\Policies\CategoryPolicy::class,
        Belt\Glue\Tag::class => Belt\Glue\Policies\TagPolicy::class,
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes/admin.php';
        include __DIR__ . '/../routes/api.php';
        include __DIR__ . '/../routes/web.php';
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(GateContract $gate, Router $router)
    {

        // set view paths
        // $this->loadViewsFrom(resource_path('belt/glue/views'), 'belt-glue');

        // set backup view paths
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'belt-glue');

        // policies
        $this->registerPolicies($gate);

        // morphMap
        Relation::morphMap([
            'categories' => Belt\Glue\Category::class,
            'tags' => Belt\Glue\Tag::class,
        ]);

        // commands
        $this->commands(Belt\Glue\Commands\PublishCommand::class);

        // route model binding
        $router->model('category', Belt\Glue\Category::class);
        $router->model('tag', Belt\Glue\Tag::class);

        # beltable values for global belt command
        $this->app['belt']->publish('belt-glue:publish');
        $this->app['belt']->seeders('BeltGlueSeeder');
    }

    /**
     * Register the application's policies.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function registerPolicies(GateContract $gate)
    {
        foreach ($this->policies as $key => $value) {
            $gate->policy($key, $value);
        }
    }

}