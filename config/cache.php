<?php

return [
    /*
     * Overrides db option if uncommented. Recommended to uncomment and enable when developing
     */
    "enabled"        => false,
    "expiresAfter"   => 604800,

    /*
     * Extension for a cache file
     */
    "html_extension" => "html",

    /*
     * Files to exclude
     */
    "exclude" => [
        "install",
        "admin",
    ],

    /*
     * Twig caching config
     */
    "twig" => [
        "caching_enabled" => false,
        "cache_dir"       => "twig",
    ],
];
