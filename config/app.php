<?php

return [
    'paths' => [
        'root'       => '.',
        'themes'     => 'themes',
        'public'     => 'public',
        'storage'    => 'storage',
        'uploads'    => '{public}/{storage}',
        'cache'      => '{public}/{themes}',
        'db'         => 'database',
        'dbfilename' => 'sqlite.db',
        'migrations' => '{db}/migrations',
        'app'        => 'app',
    ],

    'assetsDir' => 'assets',

    // obsolete
    'debug' => true,
    'theme' => "unfold",
    'theme_dev'=> true,

];

