## Installation

Add the ServiceProvider to the providers array in config/app.php

```php
Belt\Glue\BeltGlueServiceProvider::class,
```

```
# publish
php artisan belt-glue:publish
composer dumpautoload

# migration
php artisan migrate

# seed
php artisan db:seed --class=BeltGlueSeeder

# compile assets
npm run
```