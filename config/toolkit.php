<?php

return [

    'models' => [
        'tag' => \Otinsoft\Toolkit\Tags\Tag::class,
        'role' => \Otinsoft\Toolkit\Users\Role::class,
    ],

    'photo_size' => 300,
    'photo_max_filesize' => 5000, // KB
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

];
