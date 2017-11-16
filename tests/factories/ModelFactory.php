<?php

$factory->define(Otinsoft\Toolkit\Tests\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});

$factory->define(Otinsoft\Toolkit\Users\Role::class, function (Faker\Generator $faker) {
    return ['name' => $faker->unique()->word];
});
