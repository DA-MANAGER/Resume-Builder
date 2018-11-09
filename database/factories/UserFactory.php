<?php

use Faker\Generator as Faker;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name'           => $faker->name,
        'username'       => App\User::generateUsername(),
        'email'          => $faker->unique()->safeEmail,
        'password'       => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
