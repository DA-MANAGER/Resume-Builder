<?php

use Faker\Generator as Faker;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
$factory->define(App\Resume::class, function (Faker $faker) {
    $data = serialize([
        'sections' => [
            'section_1' => [
                [
                    $faker->text
                ],
                [
                    $faker->text
                ]
            ],
            'section_2' => [
                [
                    $faker->text
                ],
                [
                    $faker->text
                ]
            ],
            'section_3' => [
                [
                    $faker->text
                ],
                [
                    $faker->text
                ]
            ],
        ]
    ]);

    return [
        'data'     => $data,
        'template' => $faker->name,
        'title'    => $faker->name,
    ];
});
