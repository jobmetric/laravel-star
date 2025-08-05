<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the database tables used by the star rating system.
    | You can customize the table name if needed.
    |
    | Example:
    | 'star' => 'custom_stars_table'
    */

    "tables" => [
        'star' => 'stars',
    ],

    /*
    |--------------------------------------------------------------------------
    | Minimum Rate Value
    |--------------------------------------------------------------------------
    |
    | This sets the minimum allowed value for a star rating.
    | You can change this to match your scoring system.
    | Default: 1
    |
    | Example use-cases:
    | - A 1–5,  star system: set min = 1
    | - A 0–10, rating system: set min = 0
    */

    "min_rate" => env("STAR_MIN_RATE", 1),

    /*
    |--------------------------------------------------------------------------
    | Maximum Rate Value
    |--------------------------------------------------------------------------
    |
    | This defines the maximum allowed value for a star rating.
    | Any rating submitted beyond this value should be rejected or capped.
    | Default: 5
    |
    | Example use-cases:
    | - A 1–5,   star system: set max = 5
    | - A 0–100, score system: set max = 100
    */

    "max_rate" => env("STAR_MAX_RATE", 5),

    /*
    |--------------------------------------------------------------------------
    | Default Source Platform
    |--------------------------------------------------------------------------
    |
    | If no 'source' is provided when storing a star, this default value will be used.
    | Useful in cases where rating comes from a known environment.
    */

    "default_source" => env("STAR_DEFAULT_SOURCE", 'web'),

    /*
    |--------------------------------------------------------------------------
    | Header Names
    |--------------------------------------------------------------------------
    |
    | Header names for API requests.
    */

    "headers" => [
        'device_id' => env('STAR_HEADER_DEVICE_ID', 'X-Device-ID'),
        'source' => env('STAR_HEADER_SOURCE', 'X-Source'),
    ],


];
