## Installation

```
# install assets & migrate
php artisan belt-glue:publish
composer dumpautoload

# migrate & seed
php artisan migrate
php artisan db:seed --class=BeltGlueSeeder

# compile assets
gulp
```