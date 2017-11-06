<?php

return [

    'files_disk' => 'files',

    'models' => [
        'tag' => \Otinsoft\Toolkit\Tags\Tag::class,
        'role' => \Otinsoft\Toolkit\Users\Role::class,
        'file' => \Otinsoft\Toolkit\Files\File::class,
    ],

    'file_max_size' => 5000, // KB

    'photo_size' => 300,
    'photo_max_filesize' => 5000, // KB

    'image_driver' => env('IMAGE_DRIVER', 'gd'),

];
