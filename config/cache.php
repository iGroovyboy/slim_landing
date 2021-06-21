<?php

return [
    /*
     * Overrides db option if uncommented. Recommended to uncomment and enable when developing
     */
    // "enabled"        => false,

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
