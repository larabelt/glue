<?php

namespace Belt\Glue\Commands;

use Belt\Core\Commands\PublishCommand as Command;

class PublishCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'belt-glue:publish {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish assets for belt glue';

    protected $dirs = [
        'vendor/larabelt/glue/config' => 'config/belt',
        //'vendor/larabelt/glue/resources' => 'resources/belt/glue',
        'vendor/larabelt/glue/database/factories' => 'database/factories',
        'vendor/larabelt/glue/database/migrations' => 'database/migrations',
        'vendor/larabelt/glue/database/seeds' => 'database/seeds',
    ];

}