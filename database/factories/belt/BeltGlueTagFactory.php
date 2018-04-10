<?php

$factory->define(Belt\Glue\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(random_int(1, 2), true),
    ];
});